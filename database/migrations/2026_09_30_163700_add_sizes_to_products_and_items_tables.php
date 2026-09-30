<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'sizes')) {
            Schema::table('products', function (Blueprint $table) {
                $table->json('sizes')->nullable()->after('colors');
            });
        }

        if (!Schema::hasColumn('cart_items', 'size')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->string('size')->nullable()->after('color');
            });
        }

        if (!Schema::hasColumn('order_items', 'size')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->string('size')->nullable()->after('color');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'sizes')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('sizes');
            });
        }

        if (Schema::hasColumn('cart_items', 'size')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropColumn('size');
            });
        }

        if (Schema::hasColumn('order_items', 'size')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('size');
            });
        }
    }
};
