<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingMethod extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_rate',
        'free_threshold',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'base_rate' => 'decimal:2',
        'free_threshold' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}