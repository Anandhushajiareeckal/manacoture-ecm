<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\ManaCoutureSeeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class StorefrontAndBackendIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ManaCoutureSeeder::class);
        $this->adminUser = User::where('email', 'admin@manacouture.com')->first();
    }

    public function test_storefront_homepage_renders_with_couture_collections(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('ManaCouture');
        $response->assertSee('Woven in gold');
        $response->assertSee('Aiswarya Kasavu Saree');
        $response->assertSee('Curated Categories');
    }

    public function test_storefront_shop_page_lists_products_and_filters(): void
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('All Collections');
        $response->assertSee('Aiswarya Kasavu Saree');

        // Test category filter
        $responseCategory = $this->get('/shop?c=kasavu');
        $responseCategory->assertStatus(200);
        $responseCategory->assertSee('Kasavu Collection');
    }

    public function test_storefront_product_detail_page_renders_with_specs_and_reviews(): void
    {
        $product = Product::active()->first();
        $response = $this->get('/products/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->formatted_price);
        $response->assertSee('Add To Shopping Bag');
        $response->assertSee('Description');
    }

    public function test_cart_workflow_add_update_coupon(): void
    {
        $product = Product::active()->first();

        // 1. Add to cart
        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'qty' => 1,
            'size' => 'Free Size',
        ]);
        $response->assertRedirect('/cart');
        $response->assertSessionHas('cart');

        // 2. View cart
        $cartView = $this->get('/cart');
        $cartView->assertStatus(200);
        $cartView->assertSee($product->name);

        // 3. Apply coupon
        $couponResponse = $this->post('/cart/coupon', ['code' => 'ONAM2026']);
        $couponResponse->assertSessionHas('coupon');
    }

    public function test_checkout_creates_real_order_and_updates_inventory(): void
    {
        $product = Product::active()->first();
        $initialStock = $product->stock_qty;

        $response = $this->withSession(['cart' => [
            $product->id . '-test' => [
                'product_id' => $product->id,
                'qty' => 1,
                'size' => 'Free Size',
                'color' => 'Ivory',
            ]
        ]])->post('/checkout', [
            'email' => 'patron.test@manacouture.com',
            'first_name' => 'Meera',
            'last_name' => 'Nambiar',
            'phone' => '+91-9988776655',
            'address_line1' => 'Lotus Villa, Panampilly Nagar',
            'city' => 'Kochi',
            'state' => 'Kerala',
            'postal_code' => '682036',
            'payment_method' => 'upi',
        ]);

        


        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $response->assertRedirect('/orders/' . $order->order_number . '/confirmation');

        // Verify product inventory decreased by 1
        $this->assertEquals($initialStock - 1, $product->fresh()->stock_qty);
    }

    public function test_backend_product_edit_updates_frontend_display(): void
    {
        $product = Product::active()->first();
        $tempName = 'Aiswarya Kasavu Special Edition';

        // Update product via backend controller
        $response = $this->actingAs($this->adminUser)->put("/admin/products/{$product->id}", [
            'name' => $tempName,
            'sku' => $product->sku,
            'category_id' => $product->category_id,
            'type' => 'simple',
            'price' => 9999.00,
            'stock_qty' => $product->stock_qty,
            'low_stock_threshold' => 3,
            'status' => 'active',
        ]);
        $response->assertSessionHas('success');

        // Assert storefront reflects this change
        $storefront = $this->get('/products/' . $product->fresh()->slug);
        $storefront->assertStatus(200);
        $storefront->assertSee($tempName);
        $storefront->assertSee('₹9,999');
    }

    public function test_admin_dashboard_renders_with_manacoture_branding(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Total Revenue');
        $response->assertSee('Total Orders');
        $response->assertSee('class="content-page"', false);
    }
}
