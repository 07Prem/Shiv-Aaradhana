<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_definition_id',
        'text_value',
        'number_value',
        'json_value',
        'boolean_value',
    ];

    protected function casts(): array
    {
        return [
            'number_value' => 'decimal:4',
            'json_value' => 'array',
            'boolean_value' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeDefinition(): BelongsTo
    {
        return $this->belongsTo(AttributeDefinition::class);
    }

    public function getFormattedValueAttribute(): string
    {
        $def = $this->attributeDefinition;
        if (! $def) {
            return (string) ($this->text_value ?? $this->number_value ?? '');
        }

        $val = '';
        if ($def->type === 'number') {
            $val = (string) (float) $this->number_value;
        } elseif ($def->type === 'boolean') {
            $val = $this->boolean_value ? 'Yes' : 'No';
        } elseif ($def->type === 'multiselect' && is_array($this->json_value)) {
            $val = implode(', ', $this->json_value);
        } else {
            $val = (string) $this->text_value;
        }

        if ($def->unit && filled($val)) {
            $val .= ' ' . $def->unit;
        }

        return $val;
    }
}
