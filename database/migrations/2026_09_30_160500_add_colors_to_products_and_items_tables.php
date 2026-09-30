<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'colors')) {
            Schema::table('products', function (Blueprint $table) {
                $table->json('colors')->nullable()->after('images');
            });
        }

        if (!Schema::hasColumn('cart_items', 'color')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->string('color')->nullable()->after('product_id');
            });

            // Safely drop the 2-column unique constraint to allow multiple color variants of the same product in cart
            try {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE cart_items ADD INDEX (cart_id)');
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE cart_items DROP INDEX cart_items_cart_id_product_id_unique');
            } catch (\Throwable $e) {}
        }

        if (!Schema::hasColumn('order_items', 'color')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->string('color')->nullable()->after('product_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'colors')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('colors');
            });
        }

        if (Schema::hasColumn('cart_items', 'color')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropColumn('color');
                $table->unique(['cart_id', 'product_id']);
            });
        }

        if (Schema::hasColumn('order_items', 'color')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('color');
            });
        }
    }
};
