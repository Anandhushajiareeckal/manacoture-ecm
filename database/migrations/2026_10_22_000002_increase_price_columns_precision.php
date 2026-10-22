<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 16, 2)->change();
            $table->decimal('cost_price', 16, 2)->nullable()->change();
            $table->decimal('compare_price', 16, 2)->nullable()->change();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('price', 16, 2)->nullable()->change();
            $table->decimal('cost_price', 16, 2)->nullable()->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('unit_price', 16, 2)->change();
            $table->decimal('line_total', 16, 2)->change();
            $table->decimal('tax_amount', 16, 2)->default(0)->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 16, 2)->default(0)->change();
            $table->decimal('discount_total', 16, 2)->default(0)->change();
            $table->decimal('shipping_total', 16, 2)->default(0)->change();
            $table->decimal('tax_total', 16, 2)->default(0)->change();
            $table->decimal('grand_total', 16, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->default(0)->change();
            $table->decimal('discount_total', 12, 2)->default(0)->change();
            $table->decimal('shipping_total', 12, 2)->default(0)->change();
            $table->decimal('tax_total', 12, 2)->default(0)->change();
            $table->decimal('grand_total', 12, 2)->default(0)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('unit_price', 12, 2)->change();
            $table->decimal('line_total', 12, 2)->change();
            $table->decimal('tax_amount', 12, 2)->default(0)->change();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable()->change();
            $table->decimal('cost_price', 12, 2)->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->change();
            $table->decimal('cost_price', 12, 2)->nullable()->change();
            $table->decimal('compare_price', 12, 2)->nullable()->change();
        });
    }
};
