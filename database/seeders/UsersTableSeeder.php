<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $totalUsers = 100000;
        $batchSize = 1000;

        $this->command->info("🚀 Creating {$totalUsers} regular users with 'user' role...");

        // Hash password sekali aja
        $hashedPassword = Hash::make('password');

        // Pakai progress bar biar kelihatan prosesnya
        $bar = $this->command->getOutput()->createProgressBar($totalUsers);
        $bar->start();

        for ($i = 0; $i < $totalUsers; $i += $batchSize) {
            $currentBatchSize = min($batchSize, $totalUsers - $i);
            $usersToCreate = [];

            for ($j = 0; $j < $currentBatchSize; $j++) {
                $usersToCreate[] = [
                    'name' => fake()->name(),
                    // biar tetap unik tapi cepat
                    'email' => fake()->safeEmail() . uniqid(),
                    'password' => $hashedPassword,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                    'role' => 'user',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            try {
                DB::table('users')->insert($usersToCreate);
                unset($usersToCreate);
                $bar->advance($currentBatchSize);
            } catch (\Exception $e) {
                $this->command->error("❌ Error inserting users batch at $i: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info("✅ Successfully created {$totalUsers} users!");
    }
}
