<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SizeGuide extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'fit_type',
        'description',
        'sizes',
        'measurements',
        'default_unit',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'sizes' => 'array',
        'measurements' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get measurements with calculated inch values if entered in cm.
     */
    public function getFormattedMeasurementsAttribute(): array
    {
        $rows = $this->measurements ?? [];
        $unit = strtolower($this->default_unit ?? 'cm');

        return array_map(function ($row) use ($unit) {
            $values = $row['values'] ?? [];
            $cmValues = [];
            $inchValues = [];

            foreach ($values as $size => $val) {
                if ($val === '' || $val === null) {
                    $cmValues[$size] = '-';
                    $inchValues[$size] = '-';
                    continue;
                }

                $numeric = is_numeric($val) ? (float) $val : null;

                if ($unit === 'cm') {
                    $cmValues[$size] = (string) $val;
                    $inchValues[$size] = $numeric !== null ? (string) round($numeric / 2.54, 1) : (string) $val;
                } else {
                    $inchValues[$size] = (string) $val;
                    $cmValues[$size] = $numeric !== null ? (string) round($numeric * 2.54, 1) : (string) $val;
                }
            }

            return [
                'name' => $row['name'] ?? '',
                'unit' => $unit,
                'cm' => $cmValues,
                'inch' => $inchValues,
            ];
        }, $rows);
    }
}
