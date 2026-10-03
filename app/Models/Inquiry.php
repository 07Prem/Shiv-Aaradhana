<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inquiry extends Model
{
    use HasFactory;

    public const TYPE_GENERAL = 'general';
    public const TYPE_QUOTE = 'quote';

    public const STATUS_NEW = 'new';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RESPONDED = 'responded';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CLOSED = 'closed';
    public const STATUS_SPAM = 'spam';

    protected $fillable = [
        'reference_no',
        'inquiry_type',
        'product_id',
        'full_name',
        'company_name',
        'email',
        'phone',
        'country',
        'target_quantity',
        'packaging_requirements',
        'port_of_destination',
        'subject',
        'message',
        'status',
        'ip_address',
        'user_agent',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InquiryItem::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(InquiryActivity::class)->latest();
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if (blank($status)) {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            self::STATUS_NEW => ['label' => 'New', 'bg' => 'bg-amber-100 text-amber-800 border-amber-300'],
            self::STATUS_IN_PROGRESS => ['label' => 'In Progress', 'bg' => 'bg-blue-100 text-blue-800 border-blue-300'],
            self::STATUS_RESPONDED => ['label' => 'Responded', 'bg' => 'bg-purple-100 text-purple-800 border-purple-300'],
            self::STATUS_ACCEPTED => ['label' => 'Accepted', 'bg' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
            self::STATUS_REJECTED => ['label' => 'Rejected', 'bg' => 'bg-rose-100 text-rose-800 border-rose-300'],
            self::STATUS_CLOSED => ['label' => 'Closed', 'bg' => 'bg-stone-100 text-stone-700 border-stone-300'],
            self::STATUS_SPAM => ['label' => 'Spam', 'bg' => 'bg-rose-100 text-rose-800 border-rose-300'],
            default => ['label' => ucfirst($this->status), 'bg' => 'bg-gray-100 text-gray-800 border-gray-300'],
        };
    }
}
