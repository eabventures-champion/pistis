<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_id', 'customer_email', 'customer_name',
        'subtotal', 'tax', 'shipping_cost', 'total',
        'status', 'is_archived', 'archived_at', 'payment_method', 'payment_reference', 'payment_status',
        'admin_notified_at', 'admin_viewed_at', 'customer_notified_at',
        'shipping_address', 'billing_address', 'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
        'admin_notified_at' => 'datetime',
        'admin_viewed_at' => 'datetime',
        'customer_notified_at' => 'datetime',
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

    public function markAdminNotified(): bool
    {
        return $this->update(['admin_notified_at' => now()]);
    }

    public function markCustomerNotified(): bool
    {
        return $this->update(['customer_notified_at' => now()]);
    }

    public function markAdminViewed(): bool
    {
        if (!$this->admin_viewed_at) {
            return $this->update(['admin_viewed_at' => now()]);
        }
        return true;
    }

    public function isUnviewedByAdmin(): bool
    {
        return is_null($this->admin_viewed_at);
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function archive(): bool
    {
        return $this->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);
    }

    public function unarchive(): bool
    {
        return $this->update([
            'is_archived' => false,
            'archived_at' => null,
        ]);
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = 'PIS-' . now()->format('Ymd') . '-' . str_pad(
                    Order::whereDate('created_at', today())->count() + 1,
                    4, '0', STR_PAD_LEFT
                );
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getPaymentStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'refunded' => 'info',
            default => 'secondary',
        };
    }
}
