<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@alifshop5.uz',
            'password' => Hash::make('password1235'),
            'phone' => '+998901234567',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Test customer
        User::create([
            'name' => 'Test User',
            'email' => 'user@alifshop.uz',
            'password' => Hash::make('password123'),
            'phone' => '+998909876543',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Admin va test user yaratildi!');
        $this->command->info('📧 Admin email: admin@alifshop.uz');
        $this->command->info('🔑 Parol: password123');
    }
}
