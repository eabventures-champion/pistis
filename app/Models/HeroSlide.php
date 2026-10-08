<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class HeroSlide extends Model
{
    protected $fillable = [
        'collection_name',
        'subtitle',
        'title',
        'description',
        'image',
        'mobile_image',
        'primary_button_text',
        'primary_button_url',
        'secondary_button_text',
        'secondary_button_url',
        'sort_order',
        'display_duration',
        'is_active',
        'starts_at',
        'ends_at',
        'post_campaign_action',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'display_duration' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Automatically reset starts_at and ends_at to null for slides whose campaign
     * has concluded and are configured to remain active as evergreen slides.
     */
    public static function processExpiredSchedules(): void
    {
        $now = Carbon::now();
        static::where('post_campaign_action', 'keep_showing')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', $now)
            ->update([
                'starts_at' => null,
                'ends_at' => null,
            ]);
    }

    // Scopes
    public function scopeActive($query)
    {
        static::processExpiredSchedules();

        $now = Carbon::now();
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $now)
                    ->orWhere('post_campaign_action', 'keep_showing');
            });
    }

    public function getIsCampaignExpiredAttribute(): bool
    {
        return $this->ends_at !== null && $this->ends_at->isPast();
    }

    public function getIsCampaignScheduledAttribute(): bool
    {
        return $this->starts_at !== null || $this->ends_at !== null;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    // Accessors
    public static function formatImageUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (preg_match('#/storage/(.+)$#', $path, $matches)) {
            return '/storage/' . $matches[1];
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }
        return '/storage/' . ltrim($path, '/');
    }

    public function getImageUrlAttribute(): string
    {
        return static::formatImageUrl($this->image) ?? asset('storage/placeholder.jpg');
    }

    public function getMobileImageUrlAttribute(): string
    {
        return static::formatImageUrl($this->mobile_image) ?? $this->image_url;
    }

    public function getTitleLinesAttribute(): array
    {
        return array_filter(array_map('trim', explode("\n", (string) $this->title)));
    }
}
