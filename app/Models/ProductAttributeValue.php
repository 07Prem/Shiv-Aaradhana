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

    /**
     * Get the raw/input value for form editing and administration.
     * Preserves exact text formatting, decimal precision (e.g. '12.50'), and zero values ('0').
     */
    public function getInputValueAttribute(): string
    {
        if ($this->text_value !== null && $this->text_value !== '') {
            return (string) $this->text_value;
        }

        if ($this->number_value !== null) {
            $numStr = (string) $this->number_value;
            if (str_contains($numStr, '.')) {
                $trimmed = rtrim(rtrim($numStr, '0'), '.');
                return $trimmed === '' ? '0' : $trimmed;
            }
            return $numStr;
        }

        if ($this->boolean_value !== null) {
            return $this->boolean_value ? '1' : '0';
        }

        if (! empty($this->json_value)) {
            return is_array($this->json_value) ? implode(', ', $this->json_value) : (string) $this->json_value;
        }

        return '';
    }

    public function getFormattedValueAttribute(): string
    {
        $def = $this->attributeDefinition;
        if (! $def) {
            return (string) ($this->text_value ?? $this->number_value ?? '');
        }

        $val = '';
        if ($this->text_value !== null && $this->text_value !== '') {
            $val = (string) $this->text_value;
        } elseif ($this->number_value !== null) {
            $numStr = (string) $this->number_value;
            if (str_contains($numStr, '.')) {
                $trimmed = rtrim(rtrim($numStr, '0'), '.');
                $val = $trimmed === '' ? '0' : $trimmed;
            } else {
                $val = $numStr;
            }
        } elseif ($def->type === 'boolean' || $this->boolean_value !== null) {
            $val = $this->boolean_value ? 'Yes' : 'No';
        } elseif ($def->type === 'multiselect' && is_array($this->json_value)) {
            $val = implode(', ', $this->json_value);
        }

        if ($def->unit && filled($val)) {
            $unit = trim($def->unit);
            // Append unit only if the formatted string does not already end with or contain it
            if (! str_ends_with($val, $unit) && ! str_contains($val, $unit)) {
                $val .= ' ' . $unit;
            }
        }

        return $val;
    }
}
