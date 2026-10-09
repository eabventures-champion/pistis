<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const AVAILABLE_PERMISSIONS = [
        'hero_slides'    => ['label' => 'Hero Slider', 'desc' => 'Manage editorial hero carousel slides & lookbooks', 'group' => 'Catalog & Content', 'icon' => '🖼️'],
        'campaign_video' => ['label' => 'Campaign Video', 'desc' => 'Configure runway video modal & lookbook video', 'group' => 'Catalog & Content', 'icon' => '🎬'],
        'social_media'   => ['label' => 'Social Media', 'desc' => 'Manage floating atelier handles & channels', 'group' => 'Catalog & Content', 'icon' => '📱'],
        'products'       => ['label' => 'Products', 'desc' => 'Create, edit, and organize luxury garments & inventory', 'group' => 'Catalog & Content', 'icon' => '📦'],
        'categories'     => ['label' => 'Categories', 'desc' => 'Manage collection categories & editorial pills', 'group' => 'Catalog & Content', 'icon' => '🏷️'],
        'size_guides'    => ['label' => 'Size Guides', 'desc' => 'Manage garment measurement charts & fits', 'group' => 'Catalog & Content', 'icon' => '📏'],
        'orders'         => ['label' => 'Orders', 'desc' => 'View orders, process fulfillment, and update tracking', 'group' => 'Sales', 'icon' => '🛍️'],
        'customers'      => ['label' => 'Customers', 'desc' => 'Manage customer accounts, status, and client details', 'group' => 'Sales', 'icon' => '👥'],
        'subscribers'    => ['label' => 'Inner Circle', 'desc' => 'Manage newsletter members and broadcast lookbooks', 'group' => 'Marketing', 'icon' => '✉️'],
        'shopify'        => ['label' => 'Shopify Sync', 'desc' => 'Pull catalog from Shopify and inspect webhook logs', 'group' => 'Shopify', 'icon' => '🔄'],
        'settings'       => ['label' => 'Store Settings', 'desc' => 'Manage store branding, gateways, and postal shipping', 'group' => 'System', 'icon' => '⚙️'],
    ];

    public const ROLE_PRESETS = [
        'super_admin' => [
            'name' => 'Super Admin',
            'badge' => 'FULL ACCESS',
            'desc' => 'Complete unrestricted access to all dashboard areas, settings, and team management.',
            'permissions' => ['hero_slides', 'campaign_video', 'social_media', 'products', 'categories', 'size_guides', 'orders', 'customers', 'subscribers', 'shopify', 'settings'],
        ],
        'store_manager' => [
            'name' => 'Store Manager',
            'badge' => 'MANAGEMENT',
            'desc' => 'Can manage catalog products, orders, customers, lookbook campaigns, and Shopify sync.',
            'permissions' => ['hero_slides', 'campaign_video', 'social_media', 'products', 'categories', 'size_guides', 'orders', 'customers', 'subscribers', 'shopify'],
        ],
        'catalog_editor' => [
            'name' => 'Catalog & Content Editor',
            'badge' => 'EDITOR',
            'desc' => 'Restricted to hero slides, campaign video, products, categories, and size guides.',
            'permissions' => ['hero_slides', 'campaign_video', 'social_media', 'products', 'categories', 'size_guides'],
        ],
        'sales_fulfillment' => [
            'name' => 'Order & Fulfillment Specialist',
            'badge' => 'FULFILLMENT',
            'desc' => 'Restricted to processing orders, tracking details, and managing customer inquiries.',
            'permissions' => ['orders', 'customers'],
        ],
        'marketing_lookbook' => [
            'name' => 'Marketing & Lookbook Specialist',
            'badge' => 'MARKETING',
            'desc' => 'Restricted to Inner Circle subscribers, broadcast lookbooks, and hero banners.',
            'permissions' => ['subscribers', 'hero_slides', 'campaign_video'],
        ],
        'custom' => [
            'name' => 'Custom Role',
            'badge' => 'CUSTOM',
            'desc' => 'Individual permissions configured by the Super Admin.',
            'permissions' => [],
        ],
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_super_admin',
        'role',
        'permissions',
        'status',
        'invitation_token',
        'invitation_expires_at',
        'invitation_accepted_at',
        'invited_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'invitation_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_super_admin' => 'boolean',
            'permissions' => 'array',
            'invitation_expires_at' => 'datetime',
            'invitation_accepted_at' => 'datetime',
        ];
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin || $this->role === 'super_admin';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isPendingInvitation(): bool
    {
        return $this->status === 'invited' && !empty($this->invitation_token);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->isSuspended()) {
            return false;
        }

        $userPermissions = $this->permissions ?? [];
        if (!is_array($userPermissions)) {
            $userPermissions = [];
        }

        return in_array($permission, $userPermissions, true);
    }

    public function canAccessMenu(string $menu): bool
    {
        return $this->hasPermission($menu);
    }

    public function getRoleTitleAttribute(): string
    {
        return self::ROLE_PRESETS[$this->role]['name'] ?? ucwords(str_replace('_', ' ', $this->role ?? 'Admin'));
    }

    public function getRoleBadgeAttribute(): string
    {
        return self::ROLE_PRESETS[$this->role]['badge'] ?? 'ADMIN';
    }
}
