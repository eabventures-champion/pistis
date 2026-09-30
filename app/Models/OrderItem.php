<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'product_sku', 'color', 'size',
        'quantity', 'price', 'total',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->product) {
            return null;
        }

        // Try color-matched image first (case-insensitive)
        if ($this->color && !empty($this->product->color_galleries)) {
            $galleries = $this->product->color_galleries;
            foreach ($galleries as $colorName => $images) {
                if (strcasecmp($colorName, $this->color) === 0 && !empty($images[0])) {
                    return $images[0];
                }
            }
        }

        // Fallback to product primary image
        return $this->product->primary_image_url;
    }
}
