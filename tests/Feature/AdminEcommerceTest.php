<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\ShippingMethod;
use App\Models\Shipment;

class AdminEcommerceTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'Super Administrator',
            'slug' => 'super-admin',
            'description' => 'Full access',
        ]);

        $this->adminUser = User::create([
            'role_id' => $role->id,
            'name' => 'Admin Test User',
            'email' => 'admin.test@inspinia.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('E-Commerce Overview');
        $response->assertSee('Total Revenue');
    }

    public function test_admin_can_view_products_list(): void
    {
        $category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Gaming Mouse',
            'slug' => 'test-gaming-mouse',
            'sku' => 'TEST-MOUSE-01',
            'type' => 'simple',
            'price' => 59.99,
            'stock_qty' => 25,
            'low_stock_threshold' => 5,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.products.index'));
        $response->assertStatus(200);
        $response->assertSee('Test Gaming Mouse');
        $response->assertSee('TEST-MOUSE-01');
    }

    public function test_admin_can_create_product(): void
    {
        $category = Category::create(['name' => 'Apparel', 'slug' => 'apparel']);
        $brand = Brand::create(['name' => 'Puma', 'slug' => 'puma']);

        $response = $this->actingAs($this->adminUser)->post(route('admin.products.store'), [
            'name' => 'Cotton Graphic T-Shirt',
            'sku' => 'TSH-GR-01',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'type' => 'simple',
            'price' => 29.99,
            'cost_price' => 12.00,
            'compare_price' => 39.99,
            'stock_qty' => 50,
            'low_stock_threshold' => 10,
            'status' => 'active',
            'is_featured' => 1,
            'short_description' => 'Soft organic cotton shirt',
            'description' => 'Premium combed cotton graphic tee with ribbed collar.',
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Cotton Graphic T-Shirt',
            'sku' => 'TSH-GR-01',
            'stock_qty' => 50,
        ]);

        $this->assertDatabaseHas('inventory_movements', [
            'type' => 'stock_in',
            'qty' => 50,
        ]);
    }

    public function test_admin_can_duplicate_product(): void
    {
        $product = Product::create([
            'name' => 'Original Item',
            'slug' => 'original-item',
            'sku' => 'ORIG-SKU',
            'type' => 'simple',
            'price' => 99.00,
            'stock_qty' => 10,
            'low_stock_threshold' => 2,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.products.duplicate', $product));
        $response->assertStatus(302);

        $this->assertDatabaseHas('products', [
            'name' => 'Original Item (Copy)',
            'status' => 'draft',
        ]);
    }

    public function test_admin_can_quick_adjust_stock(): void
    {
        $product = Product::create([
            'name' => 'Stock Test Item',
            'slug' => 'stock-test-item',
            'sku' => 'STOCK-01',
            'type' => 'simple',
            'price' => 45.00,
            'stock_qty' => 10,
            'low_stock_threshold' => 3,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.products.quick-stock', $product), [
            'stock_qty' => 35,
        ]);

        $response->assertStatus(302);
        $this->assertEquals(35, $product->fresh()->stock_qty);

        $this->assertDatabaseHas('inventory_movements', [
            'product_id' => $product->id,
            'qty' => 25,
            'stock_after' => 35,
        ]);
    }

    public function test_admin_can_manage_categories(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.categories.store'), [
            'name' => 'Accessories',
            'sort_order' => 1,
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('categories', ['name' => 'Accessories']);
        $cat = Category::where('name', 'Accessories')->first();

        $updateResp = $this->actingAs($this->adminUser)->put(route('admin.categories.update', $cat), [
            'name' => 'Luxury Accessories',
            'sort_order' => 2,
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('categories', ['name' => 'Luxury Accessories']);
    }

    public function test_admin_can_view_orders_and_update_status(): void
    {
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@test.com',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-TEST-999',
            'customer_id' => $customer->id,
            'subtotal' => 150.00,
            'grand_total' => 165.00,
            'payment_status' => 'paid',
            'fulfillment_status' => 'processing',
        ]);

        // List
        $response = $this->actingAs($this->adminUser)->get(route('admin.orders.index'));
        $response->assertStatus(200);
        $response->assertSee('ORD-TEST-999');

        // Show
        $showResp = $this->actingAs($this->adminUser)->get(route('admin.orders.show', $order));
        $showResp->assertStatus(200);
        $showResp->assertSee('ORD-TEST-999');

        // Status transition
        $statusResp = $this->actingAs($this->adminUser)->patch(route('admin.orders.status', $order), [
            'fulfillment_status' => 'shipped',
            'payment_status' => 'paid',
            'note' => 'Dispatched via Express Courier',
        ]);

        $statusResp->assertStatus(302);
        $this->assertEquals('shipped', $order->fresh()->fulfillment_status);

        $this->assertDatabaseHas('order_status_history', [
            'order_id' => $order->id,
            'status' => 'Shipped',
            'note' => 'Dispatched via Express Courier',
        ]);
    }

    public function test_admin_can_view_order_invoice(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-INV-123',
            'subtotal' => 100.00,
            'grand_total' => 110.00,
            'payment_status' => 'paid',
            'fulfillment_status' => 'delivered',
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.orders.invoice', $order));
        $response->assertStatus(200);
        $response->assertSee('ORD-INV-123');
        $response->assertSee('INVOICE');
    }

    public function test_admin_can_toggle_customer_status(): void
    {
        $customer = Customer::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@test.com',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->patch(route('admin.customers.toggle-status', $customer));
        $response->assertStatus(302);
        $this->assertFalse((bool)$customer->fresh()->is_active);
    }

    public function test_admin_can_create_and_validate_coupons(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.coupons.store'), [
            'code' => 'TEST15',
            'type' => 'percent',
            'value' => 15.00,
            'min_order' => 50.00,
            'max_discount' => 30.00,
            'is_active' => 1,
        ]);

        $this->assertDatabaseHas('coupons', ['code' => 'TEST15']);
        $coupon = Coupon::where('code', 'TEST15')->first();

        $this->assertTrue($coupon->isValid(100.00));
        $this->assertEquals(15.00, $coupon->calculateDiscount(100.00));
    }

    public function test_admin_can_moderate_reviews(): void
    {
        $product = Product::create([
            'name' => 'Reviewed Prod',
            'slug' => 'reviewed-prod',
            'sku' => 'REV-01',
            'price' => 20.00,
            'stock_qty' => 5,
        ]);

        $review = Review::create([
            'product_id' => $product->id,
            'rating' => 5,
            'title' => 'Awesome Product',
            'body' => 'Really happy with this item!',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)->patch(route('admin.reviews.status', $review), [
            'status' => 'approved',
        ]);

        $response->assertStatus(302);
        $this->assertEquals('approved', $review->fresh()->status);
    }

    public function test_admin_can_export_orders_and_products_csv(): void
    {
        $ordersExport = $this->actingAs($this->adminUser)->get(route('admin.reports.export-orders'));
        $ordersExport->assertStatus(200);
        $ordersExport->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $prodsExport = $this->actingAs($this->adminUser)->get(route('admin.reports.export-products'));
        $prodsExport->assertStatus(200);
        $prodsExport->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_can_update_product_with_large_price(): void
    {
        $product = Product::create([
            'name' => 'High Value Silk Saree',
            'slug' => 'high-value-silk-saree',
            'sku' => 'HVAL-001',
            'type' => 'simple',
            'price' => 8499.00,
            'stock_qty' => 5,
            'low_stock_threshold' => 1,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->put(route('admin.products.update', $product), [
            'name' => 'High Value Silk Saree Updated',
            'sku' => 'HVAL-001',
            'type' => 'simple',
            'price' => 849999999999.00,
            'stock_qty' => 5,
            'low_stock_threshold' => 1,
            'status' => 'active',
            'is_new' => 0,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('849999999999.00', $product->fresh()->price);
    }

    public function test_admin_product_update_validates_maximum_price(): void
    {
        $product = Product::create([
            'name' => 'Test Saree',
            'slug' => 'test-saree-val',
            'sku' => 'TVAL-001',
            'type' => 'simple',
            'price' => 1000.00,
            'stock_qty' => 5,
            'low_stock_threshold' => 1,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->adminUser)->put(route('admin.products.update', $product), [
            'name' => 'Test Saree',
            'sku' => 'TVAL-001',
            'type' => 'simple',
            'price' => 9999999999999999.00,
            'stock_qty' => 5,
            'low_stock_threshold' => 1,
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['price']);
    }
}
