<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shopify_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // push, pull, inventory_update, webhook
            $table->enum('status', ['success', 'failed', 'pending'])->default('pending');
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'action']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shopify_sync_logs');
    }
};
