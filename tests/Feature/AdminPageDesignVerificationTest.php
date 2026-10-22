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
use App\Models\OrderReturn;
use App\Models\ShippingMethod;
use App\Models\Shipment;

class AdminPageDesignVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Product $testProduct;
    protected Order $testOrder;
    protected Customer $testCustomer;
    protected OrderReturn $testReturn;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'Super Administrator',
            'slug' => 'super-admin',
            'description' => 'Full administrative access',
        ]);

        $this->adminUser = User::create([
            'role_id' => $role->id,
            'name' => 'Design QA Auditor',
            'email' => 'auditor@inspinia.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        $brand = Brand::create(['name' => 'AudioTech', 'slug' => 'audiotech']);

        $this->testProduct = Product::create([
            'name' => 'Pro Noise Cancelling Headphones',
            'slug' => 'pro-noise-cancelling-headphones',
            'sku' => 'AUD-NC-99',
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'type' => 'simple',
            'price' => 199.99,
            'stock_qty' => 25,
            'status' => 'active',
        ]);

        $this->testCustomer = Customer::create([
            'first_name' => 'Eleanor',
            'last_name' => 'Vance',
            'email' => 'eleanor.vance@example.com',
            'phone' => '+1-555-0199',
            'is_active' => true,
        ]);

        $this->testOrder = Order::create([
            'order_number' => 'ORD-QA-777',
            'customer_id' => $this->testCustomer->id,
            'subtotal' => 199.99,
            'grand_total' => 219.99,
            'payment_status' => 'paid',
            'fulfillment_status' => 'processing',
        ]);

        $this->testReturn = OrderReturn::create([
            'return_number' => 'RMA-QA-101',
            'order_id' => $this->testOrder->id,
            'customer_id' => $this->testCustomer->id,
            'status' => 'requested',
            'reason' => 'Defective zipper',
            'refund_amount' => 199.99,
        ]);

        Coupon::create([
            'code' => 'SUMMER25',
            'type' => 'percent',
            'value' => 25,
            'min_order' => 50,
            'is_active' => true,
        ]);

        Review::create([
            'product_id' => $this->testProduct->id,
            'rating' => 5,
            'title' => 'Stunning quality',
            'body' => 'Best headphones I have ever bought!',
            'status' => 'approved',
        ]);

        ShippingMethod::create([
            'name' => 'Standard Ground',
            'carrier' => 'FedEx',
            'price' => 9.99,
            'is_active' => true,
        ]);

        Shipment::create([
            'order_id' => $this->testOrder->id,
            'tracking_number' => 'TRK-99887766',
            'carrier' => 'FedEx',
            'status' => 'shipped',
        ]);
    }

    private function assertInspiniaLayoutIntegrity($response): void
    {
        $response->assertStatus(200);
        $response->assertSee('assets/css/vendors.min.css', false);
        $response->assertSee('assets/css/app.min.css', false);
        $response->assertSee('assets/js/vendors.min.js', false);
        $response->assertSee('assets/js/app.js', false);
        $response->assertSee('sidenav-menu', false);
        $response->assertSee('app-topbar', false);
        $response->assertSee('class="content-page"', false);
        $response->assertSee('class="footer"', false);
    }

    public function test_auth_login_page_renders_with_inspinia_auth_layout(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('assets/css/vendors.min.css', false);
        $response->assertSee('assets/css/app.min.css', false);
        $response->assertSee('auth-box', false);
        $response->assertSee('Sign In');
    }

    public function test_admin_dashboard_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Dashboard');
        $response->assertSee('Total Revenue');
    }

    public function test_admin_products_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.products.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Pro Noise Cancelling Headphones');
        $response->assertSee('AUD-NC-99');
    }

    public function test_admin_products_create_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.products.create'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Add Product');
        $response->assertSee('General Information');
    }

    public function test_admin_products_show_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.products.show', $this->testProduct));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Pro Noise Cancelling Headphones');
        $response->assertSee('AUD-NC-99');
    }

    public function test_admin_products_edit_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.products.edit', $this->testProduct));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Edit Product');
        $response->assertSee('AUD-NC-99');
    }

    public function test_admin_categories_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.categories.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Categories');
        $response->assertSee('Electronics');
    }

    public function test_admin_attributes_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.attributes.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Product Attributes');
    }

    public function test_admin_brands_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.brands.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Brands');
        $response->assertSee('AudioTech');
    }

    public function test_admin_orders_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.orders.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Orders');
        $response->assertSee('ORD-QA-777');
    }

    public function test_admin_orders_create_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.orders.create'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Create Order');
    }

    public function test_admin_orders_show_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.orders.show', $this->testOrder));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('ORD-QA-777');
        $response->assertSee('Print Invoice');
    }

    public function test_admin_orders_invoice_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.orders.invoice', $this->testOrder));
        $response->assertStatus(200);
        $response->assertSee('assets/css/vendors.min.css', false);
        $response->assertSee('assets/css/app.min.css', false);
        $response->assertSee('ORD-QA-777');
        $response->assertSee('INVOICE');
    }

    public function test_admin_returns_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.returns.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Customer Returns');
        $response->assertSee('RMA-QA-101');
    }

    public function test_admin_returns_show_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.returns.show', $this->testReturn));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('RMA-QA-101');
        $response->assertSee('Return Request Details');
    }

    public function test_admin_customers_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.customers.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Customers');
        $response->assertSee('Eleanor Vance');
    }

    public function test_admin_customers_show_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.customers.show', $this->testCustomer));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Eleanor Vance');
        $response->assertSee('Customer Details');
    }

    public function test_admin_inventory_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.inventory.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Stock Levels');
        $response->assertSee('AUD-NC-99');
    }

    public function test_admin_inventory_movements_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.inventory.movements'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Inventory Movement Ledger');
    }

    public function test_admin_shipping_shipments_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.shipping.shipments'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Shipments');
        $response->assertSee('TRK-99887766');
    }

    public function test_admin_shipping_methods_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.shipping.methods'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Shipping Methods');
        $response->assertSee('Standard Ground');
    }

    public function test_admin_coupons_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.coupons.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Coupons & Discounts');
        $response->assertSee('SUMMER25');
    }

    public function test_admin_reviews_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.reviews.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Customer Reviews');
        $response->assertSee('Stunning quality');
    }

    public function test_admin_reports_sales_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.reports.sales'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Sales Analytics Report');
    }

    public function test_admin_settings_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.settings.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Store Settings');
    }

    public function test_admin_users_index_design_integrity(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.users.index'));
        $this->assertInspiniaLayoutIntegrity($response);
        $response->assertSee('Administrative Staff');
        $response->assertSee('auditor@inspinia.com');
    }
}
