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
        'category_id', 'size_guide_id', 'name', 'slug', 'description', 
        'details_and_fit', 'shipping_and_returns', 'garment_care',
        'price', 'compare_price',
        'sku', 'stock_quantity', 'images', 'colors', 'sizes', 'shopify_product_id', 'shopify_variant_id',
        'shopify_inventory_item_id', 'shopify_sync_enabled', 'status', 'featured', 'weight',
    ];

    protected $casts = [
        'images' => 'array',
        'colors' => 'array',
        'sizes' => 'array',
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

    public function sizeGuide(): BelongsTo
    {
        return $this->belongsTo(SizeGuide::class);
    }

    public function resolveSizeGuide(): ?SizeGuide
    {
        if ($this->size_guide_id && $this->sizeGuide && $this->sizeGuide->is_active) {
            return $this->sizeGuide;
        }

        // Try direct category
        if ($this->category_id) {
            $catGuide = SizeGuide::where('category_id', $this->category_id)->where('is_active', true)->first();
            if ($catGuide) {
                return $catGuide;
            }

            // Walk parent categories
            $parent = $this->category ? $this->category->parent : null;
            while ($parent) {
                $parentGuide = SizeGuide::where('category_id', $parent->id)->where('is_active', true)->first();
                if ($parentGuide) {
                    return $parentGuide;
                }
                $parent = $parent->parent;
            }
        }

        return SizeGuide::where('is_active', true)->orderBy('sort_order')->first();
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
        if (!empty($images) && count($images) > 0) {
            return $images[0];
        }
        if (!empty($this->colors) && is_array($this->colors)) {
            foreach ($this->colors as $color) {
                if (!empty($color['images']) && is_array($color['images']) && count($color['images']) > 0) {
                    return $color['images'][0];
                }
            }
        }
        return null;
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        return static::formatImageUrl($this->primary_image);
    }

    public function getImageUrlsAttribute(): array
    {
        $urls = [];
        if (!empty($this->images) && is_array($this->images)) {
            $urls = array_values(array_filter(array_map(fn($img) => static::formatImageUrl($img), $this->images)));
        }
        if (empty($urls) && !empty($this->colors) && is_array($this->colors)) {
            foreach ($this->colors as $color) {
                if (!empty($color['images']) && is_array($color['images'])) {
                    foreach ($color['images'] as $img) {
                        $formatted = static::formatImageUrl($img);
                        if ($formatted && !in_array($formatted, $urls)) {
                            $urls[] = $formatted;
                        }
                    }
                }
            }
        }
        return $urls;
    }

    public function getFormattedPriceAttribute(): string
    {
        return \App\Models\Setting::get('currency_symbol', '$') . number_format($this->price, 2);
    }

    public function getFormattedComparePriceAttribute(): ?string
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return \App\Models\Setting::get('currency_symbol', '$') . number_format($this->compare_price, 2);
        }
        return null;
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
                    $images = [];
                    if (!empty($c['images']) && is_array($c['images'])) {
                        $images = array_values(array_filter($c['images']));
                    }
                    $imageUrls = array_values(array_filter(array_map(fn($img) => static::formatImageUrl($img), $images)));

                    $list[] = [
                        'name' => $name,
                        'code' => !empty($c['code']) ? $c['code'] : static::defaultHexForColorName($name),
                        'images' => $images,
                        'image_urls' => $imageUrls,
                        'primary_image_url' => $imageUrls[0] ?? null,
                    ];
                }
            } elseif (is_string($c)) {
                $name = trim($c);
                if ($name !== '') {
                    $list[] = [
                        'name' => $name,
                        'code' => static::defaultHexForColorName($name),
                        'images' => [],
                        'image_urls' => [],
                        'primary_image_url' => null,
                    ];
                }
            }
        }
        return $list;
    }

    public function getColorGalleriesAttribute(): array
    {
        $galleries = [];
        $fallback = $this->image_urls;

        foreach ($this->colors_list as $color) {
            $name = $color['name'];
            $urls = !empty($color['image_urls']) ? $color['image_urls'] : $fallback;
            $galleries[$name] = $urls;
        }

        return $galleries;
    }

    public function getSizesListAttribute(): array
    {
        if (empty($this->sizes) || !is_array($this->sizes)) {
            return [];
        }
        $list = [];
        foreach ($this->sizes as $s) {
            $s = trim((string) $s);
            if ($s !== '') {
                $list[] = $s;
            }
        }
        return array_values(array_unique($list));
    }

    public function getResolvedDetailsAndFitAttribute(): string
    {
        if (!empty($this->details_and_fit)) {
            return $this->details_and_fit;
        }

        return Setting::get(
            'default_details_and_fit',
            "Heavyweight 390 GSM premium cotton fleece fabrication\nVintage pigment dye treatment for deep, washed texture\nRelaxed 90s fit with dropped shoulders and structured drape\nRib-knit collar, cuffs, and hem with reinforced needle stitching\nSignature archival branding and functional kangaroo pocket"
        );
    }

    public function getResolvedShippingAndReturnsAttribute(): string
    {
        if (!empty($this->shipping_and_returns)) {
            return $this->shipping_and_returns;
        }

        return Setting::get(
            'default_shipping_and_returns',
            "All orders are dispatched from our atelier within 24–48 hours with full tracking details sent via email.\n\nComplimentary exchanges and returns are accepted within 14 days of delivery. Items must be in original unworn condition with tags attached."
        );
    }

    public function getResolvedGarmentCareAttribute(): string
    {
        if (!empty($this->garment_care)) {
            return $this->garment_care;
        }

        return Setting::get(
            'default_garment_care',
            "Machine wash cold inside-out on gentle cycle with like colors.\n\nDo not bleach. Lay flat to dry or tumble dry on lowest temperature.\n\nCool iron on reverse if necessary; do not iron directly on graphic accents."
        );
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }
}
