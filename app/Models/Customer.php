<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $guard = 'customer';

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone',
        'address', 'city', 'state', 'country', 'postal_code', 'password',
        'archived_at', 'is_disabled', 'disabled_at', 'disabled_reason',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'archived_at' => 'datetime',
        'is_disabled' => 'boolean',
        'disabled_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at')->where('is_disabled', false);
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function scopeDisabled($query)
    {
        return $query->where('is_disabled', true);
    }

    public function archive()
    {
        $this->update(['archived_at' => now()]);
    }

    public function unarchive()
    {
        $this->update(['archived_at' => null]);
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    public function disable(?string $reason = null)
    {
        $this->update([
            'is_disabled' => true,
            'disabled_at' => now(),
            'disabled_reason' => $reason,
        ]);
    }

    public function enable()
    {
        $this->update([
            'is_disabled' => false,
            'disabled_at' => null,
            'disabled_reason' => null,
        ]);
    }

    public function isDisabled(): bool
    {
        return (bool) $this->is_disabled;
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->isArchived()) {
            return 'Archived';
        }
        if ($this->isDisabled()) {
            return 'Disabled';
        }
        return 'Active';
    }

    public function getStatusBadgeAttribute(): string
    {
        if ($this->isArchived()) {
            return 'secondary';
        }
        if ($this->isDisabled()) {
            return 'danger';
        }
        return 'success';
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }
}
