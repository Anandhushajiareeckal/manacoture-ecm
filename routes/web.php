<?php

use Illuminate\Support\Facades\Route;

// Storefront Controllers
use App\Http\Controllers\Storefront\HomeController;
use App\Http\Controllers\Storefront\ShopController;
use App\Http\Controllers\Storefront\ProductController as StorefrontProductController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Storefront\CheckoutController;
use App\Http\Controllers\Storefront\WishlistController;
use App\Http\Controllers\Storefront\PageController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Storefront Routes (SSR Blade + Tailwind, Kerala Luxury Couture)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/products/{slug}', [StorefrontProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/orders/{orderNumber}/confirmation', [CheckoutController::class, 'confirmation'])->name('orders.confirmation');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/account', [PageController::class, 'account'])->name('account');
Route::get('/customer/auth', [PageController::class, 'auth'])->name('customer.auth');

/*
|--------------------------------------------------------------------------
| Admin Backend Routes (/admin/*)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::post('products/{product}/duplicate', [AdminProductController::class, 'duplicate'])->name('products.duplicate');
    Route::post('products/{product}/quick-stock', [AdminProductController::class, 'quickStock'])->name('products.quick-stock');
    Route::resource('products', AdminProductController::class);

    // Categories & Brands
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('brands', BrandController::class)->except(['create', 'show', 'edit']);

    // Attributes
    Route::resource('attributes', AttributeController::class)->except(['create', 'show', 'edit']);
    Route::post('attributes/{attribute}/values', [AttributeController::class, 'storeValue'])->name('attributes.values.store');
    Route::delete('attribute-values/{attributeValue}', [AttributeController::class, 'destroyValue'])->name('attributes.values.destroy');

    // Orders
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::resource('orders', OrderController::class)->except(['edit', 'update', 'destroy']);

    // Customers
    Route::patch('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle-status');
    Route::resource('customers', CustomerController::class);

    // Inventory
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/movements', [InventoryController::class, 'movements'])->name('inventory.movements');
    Route::post('inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');

    // Coupons
    Route::patch('coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');
    Route::resource('coupons', CouponController::class)->except(['create', 'show', 'edit']);

    // Reviews
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/status', [ReviewController::class, 'updateStatus'])->name('reviews.status');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Shipping
    Route::get('shipping/methods', [ShippingController::class, 'methods'])->name('shipping.methods');
    Route::post('shipping/methods', [ShippingController::class, 'storeMethod'])->name('shipping.methods.store');
    Route::put('shipping/methods/{shippingMethod}', [ShippingController::class, 'updateMethod'])->name('shipping.methods.update');
    Route::get('shipping/shipments', [ShippingController::class, 'shipments'])->name('shipping.shipments');
    Route::patch('shipping/shipments/{shipment}', [ShippingController::class, 'updateShipment'])->name('shipping.shipments.update');

    // Returns
    Route::get('returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('returns/{orderReturn}', [ReturnController::class, 'show'])->name('returns.show');
    Route::patch('returns/{orderReturn}/status', [ReturnController::class, 'updateStatus'])->name('returns.status');

    // Reports
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/export-orders', [ReportController::class, 'exportOrders'])->name('reports.export-orders');
    Route::get('reports/export-products', [ReportController::class, 'exportProducts'])->name('reports.export-products');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');

    // Users Management
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

    // Profile under admin prefix
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Default Breeze profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Default dashboard redirect for Breeze
Route::get('/dashboard', function() {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
