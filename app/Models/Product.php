<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 'compare_price',
        'sku', 'stock_quantity', 'images', 'colors', 'shopify_product_id', 'shopify_variant_id',
        'shopify_inventory_item_id', 'shopify_sync_enabled', 'status', 'featured', 'weight',
    ];

    protected $casts = [
        'images' => 'array',
        'colors' => 'array',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'shopify_sync_enabled' => 'boolean',
        'featured' => 'boolean',
        'weight' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = 'PIS-' . strtoupper(Str::random(8));
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getRootCategoryId(): ?int
    {
        $cat = $this->category;
        if (!$cat) {
            return null;
        }
        while ($cat->parent) {
            $cat = $cat->parent;
        }
        return $cat->id;
    }

    public function getRootCategoryName(): ?string
    {
        $cat = $this->category;
        if (!$cat) {
            return null;
        }
        while ($cat->parent) {
            $cat = $cat->parent;
        }
        return $cat->name;
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function syncLogs(): HasMany
    {
        return $this->hasMany(ShopifySyncLog::class);
    }

    // Helpers & Accessors
    public static function formatImageUrl(?string $image): ?string
    {
        if (!$image) {
            return null;
        }
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '//')) {
            return $image;
        }
        return asset('storage/' . $image);
    }

    public function getPrimaryImageAttribute(): ?string
    {
        $images = $this->images;
        return $images && count($images) > 0 ? $images[0] : null;
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        return static::formatImageUrl($this->primary_image);
    }

    public function getImageUrlsAttribute(): array
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }
        return array_values(array_filter(array_map(fn($img) => static::formatImageUrl($img), $this->images)));
    }

    public function getFormattedPriceAttribute(): string
    {
        return \App\Models\Setting::get('currency_symbol', '$') . number_format($this->price, 2);
    }


    public function getIsInStockAttribute(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function getIsSyncedToShopifyAttribute(): bool
    {
        return !is_null($this->shopify_product_id);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeSyncable($query)
    {
        return $query->where('shopify_sync_enabled', true);
    }

    public static function defaultHexForColorName(string $name): string
    {
        $map = [
            'black' => '#000000',
            'noir' => '#000000',
            'grey' => '#737373',
            'gray' => '#737373',
            'heather grey' => '#8a8a8a',
            'light grey' => '#d4d4d8',
            'dark grey' => '#3f3f46',
            'charcoal' => '#262626',
            'white' => '#ffffff',
            'blanc' => '#ffffff',
            'off-white' => '#f5f5f0',
            'cream' => '#f5f5dc',
            'beige' => '#d4c5b9',
            'sand' => '#c2b280',
            'navy' => '#0f172a',
            'midnight' => '#090d16',
            'brown' => '#4a3728',
            'mocha' => '#5c4033',
            'olive' => '#3d4a3e',
            'forest' => '#1e3a1e',
            'khaki' => '#a39b8b',
        ];
        $key = strtolower(trim($name));
        return $map[$key] ?? '#525252';
    }

    public function getColorsListAttribute(): array
    {
        if (empty($this->colors)) {
            return [];
        }
        $list = [];
        foreach ($this->colors as $c) {
            if (is_array($c)) {
                $name = trim($c['name'] ?? '');
                if ($name !== '') {
                    $list[] = [
                        'name' => $name,
                        'code' => !empty($c['code']) ? $c['code'] : static::defaultHexForColorName($name),
                    ];
                }
            } elseif (is_string($c)) {
                $name = trim($c);
                if ($name !== '') {
                    $list[] = [
                        'name' => $name,
                        'code' => static::defaultHexForColorName($name),
                    ];
                }
            }
        }
        return $list;
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }
}
