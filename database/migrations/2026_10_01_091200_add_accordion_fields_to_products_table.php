<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('details_and_fit')->nullable()->after('description');
            $table->text('shipping_and_returns')->nullable()->after('details_and_fit');
            $table->text('garment_care')->nullable()->after('shipping_and_returns');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['details_and_fit', 'shipping_and_returns', 'garment_care']);
        });
    }
};
