<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Asosiy kategoriyalar
        $telefonlar = Category::create([
            'name' => 'Telefonlar',
            'slug' => 'telefonlar',
            'description' => 'Smartfonlar va telefon aksessuarlari',
            'is_active' => true,
            'order' => 1,
        ]);

        $noutbuklar = Category::create([
            'name' => 'Noutbuklar',
            'slug' => 'noutbuklar',
            'description' => 'Noutbuklar, planshetlar va kompyuter texnikasi',
            'is_active' => true,
            'order' => 2,
        ]);

        $televizorlar = Category::create([
            'name' => 'Televizorlar',
            'slug' => 'televizorlar',
            'description' => 'Smart TV va televizorlar',
            'is_active' => true,
            'order' => 3,
        ]);

        $maishiy = Category::create([
            'name' => 'Maishiy texnika',
            'slug' => 'maishiy-texnika',
            'description' => 'Muzlatgichlar, kir yuvish mashinalari va boshqa maishiy texnika',
            'is_active' => true,
            'order' => 4,
        ]);

        $audio = Category::create([
            'name' => 'Audio texnika',
            'slug' => 'audio-texnika',
            'description' => 'Naushniklar, kolonkalar va audio qurilmalar',
            'is_active' => true,
            'order' => 5,
        ]);

        $aqlli = Category::create([
            'name' => 'Aqlli soatlar',
            'slug' => 'aqlli-soatlar',
            'description' => 'Smartwatch va fitnes браслетlar',
            'is_active' => true,
            'order' => 6,
        ]);

        // Telefonlar ichki kategoriyalari
        Category::create([
            'name' => 'Smartfonlar',
            'slug' => 'smartfonlar',
            'description' => 'Android va iOS smartfonlar',
            'parent_id' => $telefonlar->id,
            'is_active' => true,
            'order' => 1,
        ]);

        Category::create([
            'name' => 'Telefon aksessuarlari',
            'slug' => 'telefon-aksessuarlari',
            'description' => 'Chexollar, himoya oynalari, zaryadlovchilar',
            'parent_id' => $telefonlar->id,
            'is_active' => true,
            'order' => 2,
        ]);

        // Noutbuklar ichki kategoriyalari
        Category::create([
            'name' => 'Gaming noutbuklar',
            'slug' => 'gaming-noutbuklar',
            'description' => 'O\'yin uchun kuchli noutbuklar',
            'parent_id' => $noutbuklar->id,
            'is_active' => true,
            'order' => 1,
        ]);

        Category::create([
            'name' => 'Ish uchun noutbuklar',
            'slug' => 'ish-uchun-noutbuklar',
            'description' => 'Ofis va biznes uchun noutbuklar',
            'parent_id' => $noutbuklar->id,
            'is_active' => true,
            'order' => 2,
        ]);

        Category::create([
            'name' => 'Planshetlar',
            'slug' => 'planshetlar',
            'description' => 'iPad va Android planshetlar',
            'parent_id' => $noutbuklar->id,
            'is_active' => true,
            'order' => 3,
        ]);

        // Maishiy texnika ichki kategoriyalari
        Category::create([
            'name' => 'Muzlatgichlar',
            'slug' => 'muzlatgichlar',
            'description' => 'Ikkita va bitta kamerali muzlatgichlar',
            'parent_id' => $maishiy->id,
            'is_active' => true,
            'order' => 1,
        ]);

        Category::create([
            'name' => 'Kir yuvish mashinalari',
            'slug' => 'kir-yuvish-mashinalari',
            'description' => 'Avtomat kir yuvish mashinalari',
            'parent_id' => $maishiy->id,
            'is_active' => true,
            'order' => 2,
        ]);

        Category::create([
            'name' => 'Konditsionerlar',
            'slug' => 'konditsionerlar',
            'description' => 'Split sistema va konditsionerlar',
            'parent_id' => $maishiy->id,
            'is_active' => true,
            'order' => 3,
        ]);

        // Audio ichki kategoriyalari
        Category::create([
            'name' => 'Naushniklar',
            'slug' => 'naushniklar',
            'description' => 'Simsiz va simli naushniklar',
            'parent_id' => $audio->id,
            'is_active' => true,
            'order' => 1,
        ]);

        Category::create([
            'name' => 'Kolonkalar',
            'slug' => 'kolonkalar',
            'description' => 'Portativ va uy uchun kolonkalar',
            'parent_id' => $audio->id,
            'is_active' => true,
            'order' => 2,
        ]);
    }
}
