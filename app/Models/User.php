<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_CATALOG_MANAGER = 'catalog_manager';
    public const ROLE_INQUIRY_MANAGER = 'inquiry_manager';
    public const ROLE_CONTENT_EDITOR = 'content_editor';
    public const ROLE_CUSTOMER = 'customer';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'company_name',
        'address',
        'role',
        'is_active',
        'last_login',
        'last_password_change',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login' => 'datetime',
            'last_password_change' => 'datetime',
        ];
    }

    public function inquiryActivities(): HasMany
    {
        return $this->hasMany(InquiryActivity::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function canManageCatalog(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_CATALOG_MANAGER]);
    }

    public function canManageInquiries(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_INQUIRY_MANAGER]);
    }

    public function canEditContent(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_CONTENT_EDITOR]);
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }
}
