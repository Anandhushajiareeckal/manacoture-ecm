<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Customer;
use App\Models\ShippingMethod;
use App\Models\Coupon;
use App\Models\Review;

class ManaCoutureSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Administrator', 'description' => 'Full administrative access']
        );

        $managerRole = Role::firstOrCreate(
            ['slug' => 'shop-manager'],
            ['name' => 'Shop Manager', 'description' => 'Catalog and order management']
        );

        // 2. Admin Users
        User::updateOrCreate(
            ['email' => 'admin@manacouture.com'],
            [
                'name' => 'ManaCouture Atelier',
                'role_id' => $adminRole->id,
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Also keep admin@inspinia.com for backward compatibility
        User::updateOrCreate(
            ['email' => 'admin@inspinia.com'],
            [
                'name' => 'Antigravity Admin',
                'role_id' => $adminRole->id,
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // 3. Brand
        $brand = Brand::firstOrCreate(
            ['slug' => 'manacouture-atelier'],
            [
                'name' => 'ManaCouture Atelier',
                'description' => 'Handcrafted Kerala heirlooms & artisanal weaves.',
                'website' => 'https://manacouture.com',
                'is_active' => true,
            ]
        );

        // 4. Categories
        $categoriesData = [
            [
                'slug' => 'kerala-sarees',
                'name' => 'Kerala Sarees',
                'image' => 'images/manacoture/hero-saree.jpg',
                'description' => 'Pure handloom Kerala sarees dyed in natural ivory and adorned with certified antique gold zari.',
                'sort_order' => 1,
            ],
            [
                'slug' => 'kasavu',
                'name' => 'Kasavu Collection',
                'image' => 'images/manacoture/kasavu.jpg',
                'description' => 'Traditional Kasavu handlooms woven by master artisan families in Balaramapuram.',
                'sort_order' => 2,
            ],
            [
                'slug' => 'bridal',
                'name' => 'Bridal Collection',
                'image' => 'images/manacoture/bridal.jpg',
                'description' => 'Opulent Kanchipuram and Balaramapuram bridal silks for the timeless Kerala bride.',
                'sort_order' => 3,
            ],
            [
                'slug' => 'set-mundu',
                'name' => 'Set Mundu',
                'image' => 'images/manacoture/setmundu.jpg',
                'description' => 'Authentic two-piece Mundum Neriyathum with hand-loomed gold and temple borders.',
                'sort_order' => 4,
            ],
            [
                'slug' => 'kurtis',
                'name' => 'Kurtis',
                'image' => 'images/manacoture/kurti.jpg',
                'description' => 'Contemporary Chanderi and cotton kurtis with delicate hand embroidery.',
                'sort_order' => 5,
            ],
            [
                'slug' => 'salwar',
                'name' => 'Salwar Sets',
                'image' => 'images/manacoture/salwar.jpg',
                'description' => 'Graceful Anarkali and straight salwar suits paired with woven dupattas.',
                'sort_order' => 6,
            ],
            [
                'slug' => 'dupattas',
                'name' => 'Dupattas',
                'image' => 'images/manacoture/dupatta.jpg',
                'description' => 'Mulberry silk and Chanderi dupattas with rich border accents.',
                'sort_order' => 7,
            ],
            [
                'slug' => 'accessories',
                'name' => 'Accessories',
                'image' => 'images/manacoture/accessories.jpg',
                'description' => 'Antique 22K gold-plated temple jewelry and handcrafted heirlooms.',
                'sort_order' => 8,
            ],
        ];

        $categoryModels = [];
        foreach ($categoriesData as $c) {
            $cat = Category::updateOrCreate(
                ['slug' => $c['slug']],
                [
                    'name' => $c['name'],
                    'image' => $c['image'],
                    'description' => $c['description'],
                    'sort_order' => $c['sort_order'],
                    'is_active' => true,
                ]
            );
            $categoryModels[$c['slug']] = $cat;
        }

        // 5. Products
        $productsData = [
            [
                'name' => 'Aiswarya Kasavu Saree',
                'slug' => 'aiswarya-kasavu-saree',
                'sku' => 'MC-SAR-001',
                'category_slug' => 'kerala-sarees',
                'price' => 8499,
                'compare_price' => 10999,
                'cost_price' => 4500,
                'main_image' => 'images/manacoture/hero-saree.jpg',
                'hover_image' => 'images/manacoture/kasavu.jpg',
                'fabric' => 'Pure Kanchi Cotton',
                'color' => 'Ivory',
                'occasion' => 'Festival',
                'badge' => 'Best Seller',
                'stock_qty' => 12,
                'rating' => 4.90,
                'reviews_count' => 214,
                'is_new' => true,
                'is_featured' => true,
                'description' => 'A limited edition hand-loomed Kasavu saree with antique zari border, handwoven by master artisans in Balaramapuram. Features pure breathable combed cotton dyed in soft natural ivory and finished with an ornate pallu.',
                'short_description' => 'Handwoven in Balaramapuram with pure combed cotton and antique gold zari.',
            ],
            [
                'name' => 'Meenakshi Bridal Silk',
                'slug' => 'meenakshi-bridal-silk',
                'sku' => 'MC-BRI-002',
                'category_slug' => 'bridal',
                'price' => 34999,
                'compare_price' => 42000,
                'cost_price' => 20000,
                'main_image' => 'images/manacoture/bridal.jpg',
                'hover_image' => 'images/manacoture/hero-saree.jpg',
                'fabric' => 'Kanchipuram Silk',
                'color' => 'Cream & Maroon',
                'occasion' => 'Wedding',
                'badge' => 'Bridal',
                'stock_qty' => 4,
                'rating' => 5.00,
                'reviews_count' => 96,
                'is_new' => false,
                'is_featured' => true,
                'description' => 'An heirloom Kanchipuram silk saree with intricate temple motifs, woven with tested gold zari for the discerning bride. Crafted over 28 days of meticulous loom work.',
                'short_description' => 'Tested gold zari temple motifs on rich cream and auspicious maroon silk.',
            ],
            [
                'name' => 'Onam Traditional Kasavu',
                'slug' => 'onam-traditional-kasavu',
                'sku' => 'MC-KAS-003',
                'category_slug' => 'kasavu',
                'price' => 5299,
                'compare_price' => null,
                'cost_price' => 2800,
                'main_image' => 'images/manacoture/kasavu.jpg',
                'hover_image' => 'images/manacoture/hero-saree.jpg',
                'fabric' => 'Handloom Cotton',
                'color' => 'Off-White',
                'occasion' => 'Onam',
                'badge' => null,
                'stock_qty' => 20,
                'rating' => 4.80,
                'reviews_count' => 187,
                'is_new' => true,
                'is_featured' => true,
                'description' => 'Classic Kerala Kasavu handloom saree designed specifically for auspicious celebrations, family gatherings, and temple visits.',
                'short_description' => 'Pure handloom cotton with traditional 3-inch golden border.',
            ],
            [
                'name' => 'Devika Set Mundu',
                'slug' => 'devika-set-mundu',
                'sku' => 'MC-SET-004',
                'category_slug' => 'set-mundu',
                'price' => 6799,
                'compare_price' => 7999,
                'cost_price' => 3500,
                'main_image' => 'images/manacoture/setmundu.jpg',
                'hover_image' => 'images/manacoture/kasavu.jpg',
                'fabric' => 'Cotton Silk',
                'color' => 'Ivory & Gold',
                'occasion' => 'Temple',
                'badge' => null,
                'stock_qty' => 15,
                'rating' => 4.70,
                'reviews_count' => 143,
                'is_new' => false,
                'is_featured' => true,
                'description' => 'Traditional two-piece Kerala Mundum Neriyathum with a woven floral border. Lightweight, fluid drape, and timeless elegance.',
                'short_description' => 'Two-piece Mundum Neriyathum with floral border.',
            ],
            [
                'name' => 'Riya Embroidered Kurti',
                'slug' => 'riya-embroidered-kurti',
                'sku' => 'MC-KUR-005',
                'category_slug' => 'kurtis',
                'price' => 2299,
                'compare_price' => null,
                'cost_price' => 1100,
                'main_image' => 'images/manacoture/kurti.jpg',
                'hover_image' => 'images/manacoture/salwar.jpg',
                'fabric' => 'Chanderi Silk',
                'color' => 'Cream',
                'occasion' => 'Casual',
                'badge' => null,
                'stock_qty' => 30,
                'rating' => 4.60,
                'reviews_count' => 258,
                'is_new' => false,
                'is_featured' => true,
                'description' => 'Contemporary straight-cut Chanderi kurti tailored with subtle tonal zari embroidery along the neckline and cuffs.',
                'short_description' => 'Straight-cut Chanderi kurti with tonal zari embroidery.',
            ],
            [
                'name' => 'Nayana Anarkali Set',
                'slug' => 'nayana-anarkali-set',
                'sku' => 'MC-SAL-006',
                'category_slug' => 'salwar',
                'price' => 4599,
                'compare_price' => 5999,
                'cost_price' => 2400,
                'main_image' => 'images/manacoture/salwar.jpg',
                'hover_image' => 'images/manacoture/kurti.jpg',
                'fabric' => 'Georgette',
                'color' => 'Ivory Gold',
                'occasion' => 'Reception',
                'badge' => null,
                'stock_qty' => 18,
                'rating' => 4.80,
                'reviews_count' => 172,
                'is_new' => false,
                'is_featured' => true,
                'description' => 'Floor-length flowing Anarkali suit paired with embroidered dupatta and churidar. Perfect for receptions and evening festivities.',
                'short_description' => 'Flowing Anarkali with embroidered net dupatta.',
            ],
            [
                'name' => 'Swarna Silk Dupatta',
                'slug' => 'swarna-silk-dupatta',
                'sku' => 'MC-DUP-007',
                'category_slug' => 'dupattas',
                'price' => 1899,
                'compare_price' => null,
                'cost_price' => 950,
                'main_image' => 'images/manacoture/dupatta.jpg',
                'hover_image' => 'images/manacoture/accessories.jpg',
                'fabric' => 'Mulberry Silk',
                'color' => 'Gold',
                'occasion' => 'Festival',
                'badge' => null,
                'stock_qty' => 25,
                'rating' => 4.70,
                'reviews_count' => 132,
                'is_new' => false,
                'is_featured' => true,
                'description' => 'Pure Mulberry silk dupatta with all-over delicate butti weaving and fringed pallu. Adds an opulent touch to any solid kurta.',
                'short_description' => 'Pure Mulberry silk with delicate golden butti weave.',
            ],
            [
                'name' => 'Lakshmi Temple Jewelry',
                'slug' => 'lakshmi-temple-jewelry',
                'sku' => 'MC-ACC-008',
                'category_slug' => 'accessories',
                'price' => 3499,
                'compare_price' => 4200,
                'cost_price' => 1800,
                'main_image' => 'images/manacoture/accessories.jpg',
                'hover_image' => 'images/manacoture/dupatta.jpg',
                'fabric' => '22K Gold Plated',
                'color' => 'Antique Gold',
                'occasion' => 'Bridal',
                'badge' => 'Best Seller',
                'stock_qty' => 22,
                'rating' => 4.90,
                'reviews_count' => 201,
                'is_new' => true,
                'is_featured' => true,
                'description' => 'Antique handcrafted Goddess Lakshmi necklace and jhumka earring set with ruby-colored stones and micro pearl drops.',
                'short_description' => 'Antique gold-plated temple necklace set with ruby stones.',
            ],
        ];

        foreach ($productsData as $p) {
            $cat = $categoryModels[$p['category_slug']] ?? null;
            $product = Product::updateOrCreate(
                ['sku' => $p['sku']],
                [
                    'category_id' => $cat?->id,
                    'brand_id' => $brand->id,
                    'name' => $p['name'],
                    'slug' => $p['slug'],
                    'type' => 'simple',
                    'fabric' => $p['fabric'],
                    'color' => $p['color'],
                    'occasion' => $p['occasion'],
                    'badge' => $p['badge'],
                    'price' => $p['price'],
                    'compare_price' => $p['compare_price'],
                    'cost_price' => $p['cost_price'],
                    'stock_qty' => $p['stock_qty'],
                    'low_stock_threshold' => 3,
                    'rating' => $p['rating'],
                    'reviews_count' => $p['reviews_count'],
                    'is_new' => $p['is_new'],
                    'is_featured' => $p['is_featured'],
                    'main_image' => $p['main_image'],
                    'hover_image' => $p['hover_image'],
                    'description' => $p['description'],
                    'short_description' => $p['short_description'],
                    'status' => 'active',
                ]
            );

            // Seed 2 gallery images for each product
            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'sort_order' => 0],
                ['path' => $p['main_image'], 'alt_text' => $p['name'], 'is_main' => true]
            );
            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'sort_order' => 1],
                ['path' => $p['hover_image'], 'alt_text' => $p['name'] . ' Detail', 'is_main' => false]
            );
        }

        // 6. Shipping Methods
        ShippingMethod::firstOrCreate(
            ['name' => 'Complimentary Ground Shipping'],
            [
                'description' => 'BlueDart / Delhivery · 3-5 business days across India',
                'base_rate' => 0.00,
                'free_threshold' => 0.00,
                'is_active' => true,
            ]
        );

        ShippingMethod::firstOrCreate(
            ['name' => 'Express Atelier Courier'],
            [
                'description' => 'FedEx Express · Insured 1-2 day expedited dispatch',
                'base_rate' => 450.00,
                'free_threshold' => null,
                'is_active' => true,
            ]
        );

        // 7. Coupons
        Coupon::firstOrCreate(
            ['code' => 'ONAM2026'],
            [
                'description' => 'Onam Festival 15% Atelier Privilege',
                'type' => 'percent',
                'value' => 15.00,
                'min_order' => 3000.00,
                'max_uses' => 500,
                'is_active' => true,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'WELCOME10'],
            [
                'description' => 'Welcome 10% Discount for new atelier clients',
                'type' => 'percent',
                'value' => 10.00,
                'min_order' => 2000.00,
                'max_uses' => 1000,
                'is_active' => true,
            ]
        );

        // 8. Sample Customer
        $customer = Customer::firstOrCreate(
            ['email' => 'aparna.menon@example.com'],
            [
                'first_name' => 'Aparna',
                'last_name' => 'Menon',
                'phone' => '+91-98470-11223',
                'is_active' => true,
            ]
        );
        \App\Models\CustomerAddress::firstOrCreate(
            ['customer_id' => $customer->id],
            [
                'type' => 'shipping',
                'full_name' => 'Aparna Menon',
                'address_line1' => 'Atelier House, Princess Street',
                'address_line2' => 'Fort Kochi',
                'city' => 'Kochi',
                'state' => 'Kerala',
                'postal_code' => '682001',
                'country' => 'IN',
                'phone' => '+91-98470-11223',
                'is_default' => true,
            ]
        );

        // 9. Sample Review
        $firstProduct = Product::first();
        if ($firstProduct) {
            Review::firstOrCreate(
                ['product_id' => $firstProduct->id, 'customer_id' => $customer->id],
                [
                    'rating' => 5,
                    'title' => 'An absolute heirloom piece!',
                    'body' => 'My wedding Kasavu felt like an heirloom the moment I unfolded it. The gold border catches light in the most beautiful way. Thank you ManaCouture!',
                    'status' => 'approved',
                ]
            );
        }
    }
}
