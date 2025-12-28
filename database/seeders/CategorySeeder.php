<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Parent Categories
        $electronics = Category::create([
            'name' => 'Elektronika',
            'slug' => 'elektronika',
            'is_active' => true,
            'order' => 1
        ]);

        $fashion = Category::create([
            'name' => 'Kiyim-kechak',
            'slug' => 'kiyim-kechak',
            'is_active' => true,
            'order' => 2
        ]);

        $home = Category::create([
            'name' => 'Uy-ro\'zg\'or',
            'slug' => 'uy-rozgor',
            'is_active' => true,
            'order' => 3
        ]);

        $sports = Category::create([
            'name' => 'Sport va hordiq',
            'slug' => 'sport-hordiq',
            'is_active' => true,
            'order' => 4
        ]);

        // Child Categories - Elektronika
        Category::create([
            'name' => 'Smartfonlar',
            'slug' => 'smartfonlar',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'order' => 1
        ]);

        Category::create([
            'name' => 'Noutbuklar',
            'slug' => 'noutbuklar',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'order' => 2
        ]);

        Category::create([
            'name' => 'Planshetlar',
            'slug' => 'planshetlar',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'order' => 3
        ]);

        Category::create([
            'name' => 'Quloqchinlar',
            'slug' => 'quloqchinlar',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'order' => 4
        ]);

        Category::create([
            'name' => 'Smart soatlar',
            'slug' => 'smart-soatlar',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'order' => 5
        ]);

        Category::create([
            'name' => 'TV va Audio',
            'slug' => 'tv-audio',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'order' => 6
        ]);

        // Child Categories - Kiyim-kechak
        Category::create([
            'name' => 'Erkaklar kiyimi',
            'slug' => 'erkaklar-kiyimi',
            'parent_id' => $fashion->id,
            'is_active' => true,
            'order' => 1
        ]);

        Category::create([
            'name' => 'Ayollar kiyimi',
            'slug' => 'ayollar-kiyimi',
            'parent_id' => $fashion->id,
            'is_active' => true,
            'order' => 2
        ]);

        Category::create([
            'name' => 'Bolalar kiyimi',
            'slug' => 'bolalar-kiyimi',
            'parent_id' => $fashion->id,
            'is_active' => true,
            'order' => 3
        ]);

        Category::create([
            'name' => 'Poyabzallar',
            'slug' => 'poyabzallar',
            'parent_id' => $fashion->id,
            'is_active' => true,
            'order' => 4
        ]);

        // Child Categories - Uy-ro'zg'or
        Category::create([
            'name' => 'Maishiy texnika',
            'slug' => 'maishiy-texnika',
            'parent_id' => $home->id,
            'is_active' => true,
            'order' => 1
        ]);

        Category::create([
            'name' => 'Mebel',
            'slug' => 'mebel',
            'parent_id' => $home->id,
            'is_active' => true,
            'order' => 2
        ]);

        Category::create([
            'name' => 'Oshxona anjomlari',
            'slug' => 'oshxona-anjomlari',
            'parent_id' => $home->id,
            'is_active' => true,
            'order' => 3
        ]);

        $this->command->info('✅ Kategoriyalar yaratildi!');
        $this->command->info('📊 4 ta parent kategoriya');
        $this->command->info('📊 13 ta child kategoriya');
    }
}
