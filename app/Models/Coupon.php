<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order',
        'max_discount',
        'max_uses',
        'uses_per_customer',
        'used_count',
        'is_active',
        'starts_at',
        'expires_at',
        'description',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'max_uses' => 'integer',
        'used_count' => 'integer',
        'uses_per_customer' => 'integer',
    ];

    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isValid(float $orderTotal = 0): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        if ($this->min_order && $orderTotal < (float)$this->min_order) return false;
        return true;
    }

    public function calculateDiscount(float $orderTotal): float
    {
        if (!$this->isValid($orderTotal)) return 0.0;
        if ($this->type === 'percent') {
            $discount = ($orderTotal * (float)$this->value) / 100;
            if ($this->max_discount && $discount > (float)$this->max_discount) {
                return (float)$this->max_discount;
            }
            return round($discount, 2);
        }
        return min((float)$this->value, $orderTotal);
    }
}