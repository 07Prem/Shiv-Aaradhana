# Shiv Aaradhana Private Limited — Production Deployment & Operations Guide

**Application:** Premium International B2B Import-Export Catalog, Product Discovery, and Inquiry Management Platform  
**Company:** Shiv Aaradhana Private Limited  
**Stack:** PHP 8.2+ / Laravel 11 / MySQL 8.0+ / Nginx / Supervisor / Redis or DB Queues  

---

## 1. System Requirements & Hosting Environment

### Web Server & Runtime
- **Operating System:** Linux (Ubuntu 22.04 LTS / 24.04 LTS recommended) or Windows Server 2022
- **PHP Version:** PHP 8.2+ or 8.3+
  - Mandatory PHP Extensions: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `json`, `mbstring`, `openssl`, `pcre`, `pdo_mysql`, `tokenizer`, `xml`, `zip`
- **Database:** MySQL 8.0+ / MariaDB 10.4+ with `utf8mb4` character set and `utf8mb4_unicode_ci` collation
- **Process Supervisor:** `supervisor` (Linux) or Windows Service Wrapper (NSSM)
- **Node.js:** Node.js 20+ LTS & npm (for asset compilation)
- **Composer:** Composer 2.x

---

## 2. Web Server Configuration (Nginx)

The web server document root must strictly point to Laravel's `/public` directory. **Never point the document root to the project root.**

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name shivaaradhana.com www.shivaaradhana.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name shivaaradhana.com www.shivaaradhana.com;

    root /var/www/shivaaradhana/public;
    index index.php index.html;

    # SSL Certificates (Let's Encrypt / Certbot)
    ssl_certificate /etc/letsencrypt/live/shivaaradhana.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/shivaaradhana.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "camera=(), microphone=(), geolocation=()";

    client_max_body_size 12M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 3. Zero-Downtime Deployment Flow

Execute the following sequential release steps on the target production server:

```bash
# 1. Navigate to release or application directory
cd /var/www/shivaaradhana

# 2. Enter maintenance mode with bypass secret
php artisan down --secret="ShivDeploySecret2026"

# 3. Pull latest signed release
git pull origin main

# 4. Install production dependencies without dev tools
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 5. Build optimized frontend assets
npm ci
npm run build

# 6. Apply database migrations
php artisan migrate --force

# 7. Optimize Laravel route, configuration, and view caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Ensure symbolic link to public storage
php artisan storage:link

# 9. Restart background queue workers
php artisan queue:restart

# 10. Exit maintenance mode
php artisan up

# 11. Run operational health check verification
curl -f https://shivaaradhana.com/health
```

---

## 4. Background Queue Worker Setup (Supervisor)

Create `/etc/supervisor/conf.d/shivaaradhana-worker.conf`:

```ini
[program:shivaaradhana-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/shivaaradhana/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/shivaaradhana/storage/logs/worker.log
stopwaitsecs=3600
```

Apply and reload supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start shivaaradhana-worker:*
```

---

## 5. Automated Backups & Disaster Recovery Strategy

### Recovery Objectives
- **Recovery Point Objective (RPO):** Maximum 24 hours of data loss (daily automated off-site snapshots).
- **Recovery Time Objective (RTO):** Under 2 hours to full operational service restoration.

### Automated Database Dump Script (`/usr/local/bin/backup-shivaaradhana.sh`)
```bash
#!/bin/bash
set -e

BACKUP_DIR="/var/backups/shivaaradhana"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
DB_NAME="shiv_aaradhana"
DB_USER="shiv_backup_user"
DB_PASS="SecureBackupPassword123!"

mkdir -p $BACKUP_DIR

# 1. Dump database with single transaction consistency
mysqldump -u $DB_USER -p$DB_PASS --single-transaction --routines --triggers $DB_NAME | gzip > "$BACKUP_DIR/db_${DB_NAME}_${TIMESTAMP}.sql.gz"

# 2. Archive product media uploads
tar -czf "$BACKUP_DIR/media_${TIMESTAMP}.tar.gz" -C /var/www/shivaaradhana/storage/app/public products/

# 3. Retain last 14 days locally; replicate to offsite storage bucket
find $BACKUP_DIR -name "*.gz" -mtime +14 -exec rm {} \;

echo "Backup completed: ${TIMESTAMP}"
```

Schedule daily execution in crontab (`crontab -e`):
```cron
0 2 * * * /usr/local/bin/backup-shivaaradhana.sh >> /var/log/backup-shivaaradhana.log 2>&1
```

---

## 6. Restore Test Verification

To restore in a non-production staging environment:
```bash
# 1. Uncompress database archive
gunzip < /var/backups/shivaaradhana/db_shiv_aaradhana_YYYYMMDD_HHMMSS.sql.gz | mysql -u root -p shiv_aaradhana_restore

# 2. Restore media assets
tar -xzf /var/backups/shivaaradhana/media_YYYYMMDD_HHMMSS.tar.gz -C /var/www/shivaaradhana/storage/app/public/
```
