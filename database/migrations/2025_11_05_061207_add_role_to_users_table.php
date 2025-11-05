<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add role column with default value 'user'
            $table->string('role', 20)->default('user')->after('password');
        });

        // Update existing admin users to have 'admin' role
        DB::statement("UPDATE users SET role = 'admin' WHERE is_admin = 1");
        
        // Drop the is_admin column after updating the role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
        });

        // Set is_admin to true for users who had 'admin' role
        DB::statement("UPDATE users SET is_admin = 1 WHERE role = 'admin'");
        
        // Drop the role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
