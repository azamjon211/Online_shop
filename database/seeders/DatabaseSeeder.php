<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            CategorySeeder::class,      // ← Bu bor
            BrandSeeder::class,          // ← Bu bor
            ProductWithImagesSeeder::class, // ← Bu bor
        ]);
    }
}
