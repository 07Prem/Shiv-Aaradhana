<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRecommendation extends Model
{
    use HasFactory;

    public const TYPE_CURATED = 'curated';
    public const TYPE_COMPLEMENTARY = 'complementary';
    public const TYPE_ALTERNATIVE = 'alternative';
    public const TYPE_RELATED = 'related';

    protected $fillable = [
        'source_product_id',
        'recommended_product_id',
        'relation_type',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'priority' => 'integer',
        ];
    }

    public function sourceProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'source_product_id');
    }

    public function recommendedProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'recommended_product_id');
    }
}
