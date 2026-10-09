<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_super_admin')->default(false)->after('is_admin');
            $table->string('role')->default('admin')->after('is_super_admin');
            $table->json('permissions')->nullable()->after('role');
            $table->string('status')->default('active')->after('permissions'); // active, invited, suspended
            $table->string('invitation_token', 64)->nullable()->unique()->after('status');
            $table->timestamp('invitation_expires_at')->nullable()->after('invitation_token');
            $table->timestamp('invitation_accepted_at')->nullable()->after('invitation_expires_at');
            $table->foreignId('invited_by')->nullable()->after('invitation_accepted_at')->constrained('users')->nullOnDelete();
        });

        // Upgrade any existing admins to super_admin
        DB::table('users')->where('is_admin', true)->update([
            'is_super_admin' => true,
            'role' => 'super_admin',
            'status' => 'active',
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['invited_by']);
            $table->dropColumn([
                'is_super_admin',
                'role',
                'permissions',
                'status',
                'invitation_token',
                'invitation_expires_at',
                'invitation_accepted_at',
                'invited_by',
            ]);
        });
    }
};
