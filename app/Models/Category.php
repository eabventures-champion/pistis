<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
 
class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'parent_id', 'sort_order', 'is_active',
    ];
 
    protected $casts = [
        'is_active' => 'boolean',
    ];
 
    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
 
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
 
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
 
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
 
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
 
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
 
    /**
     * Get all IDs in this category's hierarchy (self + children).
     */
    public function getRecursiveIds(): array
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getRecursiveIds());
        }
        return $ids;
    }

    /**
     * Get an ordered array of all ancestor Category models from root down to immediate parent.
     *
     * @return array<Category>
     */
    public function getAncestors(): array
    {
        $ancestors = [];
        $current = $this->parent;
        $visited = [$this->id];

        while ($current && !in_array($current->id, $visited)) {
            $visited[] = $current->id;
            array_unshift($ancestors, $current);
            $current = $current->parent;
        }

        return $ancestors;
    }

    /**
     * Get full breadcrumb path of parent categories ending with arrow, e.g. "Clothing → Men's Wear → "
     */
    public function getParentPathAttribute(): string
    {
        $ancestors = $this->getAncestors();
        if (empty($ancestors)) {
            return '';
        }
        return implode(' → ', array_map(fn($c) => $c->name, $ancestors)) . ' → ';
    }

    /**
     * Get full breadcrumb path including this category, e.g. "Clothing → Men's Wear → Shirts"
     */
    public function getFullPathAttribute(): string
    {
        $ancestors = $this->getAncestors();
        $names = array_map(fn($c) => $c->name, $ancestors);
        $names[] = $this->name;
        return implode(' → ', $names);
    }
 
    /**
     * Calculate total products count recursively.
     */
    public function getTotalProductsCountAttribute(): int
    {
        $count = $this->products_count ?? $this->products()->count();
        foreach ($this->children as $child) {
            $count += $child->total_products_count;
        }
        return $count;
    }

    /**
     * Get a flattened list of categories with indentation for use in select inputs.
     */
    public static function getIndentedList(): array
    {
        $categories = self::active()->topLevel()->with('children')->orderBy('sort_order')->get();
        $list = [];

        foreach ($categories as $category) {
            $list[$category->id] = $category->name;
            foreach ($category->children as $child) {
                $list[$child->id] = '— ' . $child->name;
                // Support 3rd level if needed
                if ($child->children()->count() > 0) {
                     foreach ($child->children as $grandChild) {
                         $list[$grandChild->id] = '—— ' . $grandChild->name;
                     }
                }
            }
        }

        return $list;
    }
}
