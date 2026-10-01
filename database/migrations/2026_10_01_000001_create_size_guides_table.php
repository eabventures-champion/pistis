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
        Schema::create('size_guides', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('fit_type')->nullable()->default('Standard Fit');
            $table->text('description')->nullable();
            $table->json('sizes')->nullable();
            $table->json('measurements')->nullable();
            $table->string('default_unit')->default('cm');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('size_guide_id')->nullable()->after('category_id')->constrained('size_guides')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['size_guide_id']);
            $table->dropColumn('size_guide_id');
        });

        Schema::dropIfExists('size_guides');
    }
};
