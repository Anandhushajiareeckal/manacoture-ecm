<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'type',
        'fabric',
        'color',
        'occasion',
        'badge',
        'description',
        'short_description',
        'price',
        'cost_price',
        'compare_price',
        'stock_qty',
        'low_stock_threshold',
        'weight',
        'dimensions',
        'status',
        'is_featured',
        'is_new',
        'rating',
        'reviews_count',
        'main_image',
        'hover_image',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'reviews_count' => 'integer',
        'stock_qty' => 'integer',
        'low_stock_threshold' => 'integer',
        'weight' => 'decimal:3',
        'dimensions' => 'array',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class)->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_qty', '<=', 'low_stock_threshold');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_qty <= $this->low_stock_threshold;
    }

    public function getProfitMarginAttribute(): ?float
    {
        if ($this->cost_price && $this->cost_price > 0 && $this->price > 0) {
            return round((($this->price - $this->cost_price) / $this->price) * 100, 1);
        }
        return null;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }
        return 0;
    }

    public function getMainImageUrlAttribute(): string
    {
        if ($this->main_image) {
            if (str_starts_with($this->main_image, 'http')) {
                return $this->main_image;
            }
            if (file_exists(public_path($this->main_image))) {
                return asset($this->main_image);
            }
            if (file_exists(public_path('storage/' . $this->main_image))) {
                return asset('storage/' . $this->main_image);
            }
        }
        return asset('images/manacoture/hero-saree.jpg');
    }

    public function getHoverImageUrlAttribute(): string
    {
        if ($this->hover_image) {
            if (str_starts_with($this->hover_image, 'http')) {
                return $this->hover_image;
            }
            if (file_exists(public_path($this->hover_image))) {
                return asset($this->hover_image);
            }
            if (file_exists(public_path('storage/' . $this->hover_image))) {
                return asset('storage/' . $this->hover_image);
            }
        }
        return $this->main_image_url;
    }

    public function getFormattedPriceAttribute(): string
    {
        return '₹' . number_format($this->price, 0);
    }

    public function getFormattedComparePriceAttribute(): ?string
    {
        return $this->compare_price ? '₹' . number_format($this->compare_price, 0) : null;
    }
}
