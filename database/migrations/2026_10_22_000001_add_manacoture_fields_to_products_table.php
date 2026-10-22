<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('fabric')->nullable()->after('type');
            $table->string('color')->nullable()->after('fabric');
            $table->string('occasion')->nullable()->after('color');
            $table->string('badge')->nullable()->after('occasion');
            $table->string('hover_image')->nullable()->after('main_image');
            $table->decimal('rating', 3, 2)->default(5.00)->after('is_featured');
            $table->unsignedInteger('reviews_count')->default(0)->after('rating');
            $table->boolean('is_new')->default(false)->after('reviews_count');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'fabric',
                'color',
                'occasion',
                'badge',
                'hover_image',
                'rating',
                'reviews_count',
                'is_new'
            ]);
        });
    }
};
