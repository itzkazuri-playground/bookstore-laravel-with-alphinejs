<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admin user
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@example.com'], // Use a specific email for the admin
            [
                'name' => 'furina',
                'email' => 'admin@example.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
            ]
        );
    }
}