<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'path',
        'alt_text',
        'sort_order',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->path, 'http')) return $this->path;
        if (file_exists(public_path($this->path))) return asset($this->path);
        if (file_exists(public_path('storage/' . $this->path))) return asset('storage/' . $this->path);
        return asset('assets/images/products/1.png');
    }
}