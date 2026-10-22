<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'billing_address',
        'shipping_address',
        'subtotal',
        'discount_total',
        'shipping_total',
        'tax_total',
        'grand_total',
        'payment_status',
        'fulfillment_status',
        'coupon_id',
        'payment_method',
        'notes',
        'admin_notes',
        'ip_address',
        'created_by',
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->latest();
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class)->latest();
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class)->latest();
    }

    public function returns(): HasMany
    {
        return $this->hasMany(OrderReturn::class)->latest();
    }

    public function scopeRecent($query)
    {
        return $query->latest();
    }

    public function getCustomerNameAttribute(): string
    {
        if ($this->customer) {
            return $this->customer->full_name;
        }
        if (!empty($this->shipping_address['full_name'])) {
            return $this->shipping_address['full_name'];
        }
        return 'Guest Customer';
    }

    public function getPaymentBadgeClassAttribute(): string
    {
        return match($this->payment_status) {
            'paid' => 'badge bg-success',
            'pending' => 'badge bg-warning text-dark',
            'partially_paid' => 'badge bg-info text-dark',
            'refunded', 'partially_refunded' => 'badge bg-secondary',
            'failed' => 'badge bg-danger',
            default => 'badge bg-light text-dark',
        };
    }

    public function getFulfillmentBadgeClassAttribute(): string
    {
        return match($this->fulfillment_status) {
            'delivered' => 'badge bg-primary',
            'shipped' => 'badge bg-info text-dark',
            'processing' => 'badge bg-warning text-dark',
            'cancelled' => 'badge bg-danger',
            'returned' => 'badge bg-secondary',
            'pending' => 'badge bg-secondary',
            default => 'badge bg-light text-dark',
        };
    }
}