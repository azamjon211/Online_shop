<?php
// database/seeders/BrandSeeder.php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏢 Brendlar yaratilmoqda...');

        $brands = [
            // Telefon brendlari
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'iPhone, iPad, MacBook va boshqa Apple mahsulotlari',
                'order' => 1
            ],
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Galaxy smartfonlar, planshetlar va maishiy texnika',
                'order' => 2
            ],
            [
                'name' => 'Xiaomi',
                'slug' => 'xiaomi',
                'description' => 'Arzon va sifatli smartfonlar',
                'order' => 3
            ],
            [
                'name' => 'Realme',
                'slug' => 'realme',
                'description' => 'Yoshlar uchun zamonaviy smartfonlar',
                'order' => 4
            ],
            [
                'name' => 'Oppo',
                'slug' => 'oppo',
                'description' => 'Kamera funksiyalari kuchli smartfonlar',
                'order' => 5
            ],
            [
                'name' => 'Vivo',
                'slug' => 'vivo',
                'description' => 'Innovatsion texnologiyali smartfonlar',
                'order' => 6
            ],
            [
                'name' => 'Huawei',
                'slug' => 'huawei',
                'description' => 'Telefon va planshetlar',
                'order' => 7
            ],
            [
                'name' => 'Honor',
                'slug' => 'honor',
                'description' => 'Huawei\'ning sub-brendi',
                'order' => 8
            ],
            [
                'name' => 'Tecno',
                'slug' => 'tecno',
                'description' => 'Arzon smartfonlar',
                'order' => 9
            ],
            [
                'name' => 'Infinix',
                'slug' => 'infinix',
                'description' => 'Budget smartfonlar',
                'order' => 10
            ],
            [
                'name' => 'Nokia',
                'slug' => 'nokia',
                'description' => 'Klassik va zamonaviy telefonlar',
                'order' => 11
            ],
            [
                'name' => 'OnePlus',
                'slug' => 'oneplus',
                'description' => 'Flagship killer smartfonlar',
                'order' => 12
            ],

            // Kompyuter brendlari
            [
                'name' => 'Dell',
                'slug' => 'dell',
                'description' => 'Noutbuklar va kompyuterlar',
                'order' => 13
            ],
            [
                'name' => 'HP',
                'slug' => 'hp',
                'description' => 'Hewlett-Packard noutbuklari',
                'order' => 14
            ],
            [
                'name' => 'Lenovo',
                'slug' => 'lenovo',
                'description' => 'ThinkPad va IdeaPad seriyalari',
                'order' => 15
            ],
            [
                'name' => 'Asus',
                'slug' => 'asus',
                'description' => 'Gaming va oddiy noutbuklar',
                'order' => 16
            ],
            [
                'name' => 'Acer',
                'slug' => 'acer',
                'description' => 'Arzon noutbuklar',
                'order' => 17
            ],
            [
                'name' => 'MSI',
                'slug' => 'msi',
                'description' => 'Gaming noutbuklar',
                'order' => 18
            ],

            // Audio brendlari
            [
                'name' => 'Sony',
                'slug' => 'sony',
                'description' => 'Quloqchinlar, kolonkalar va televizorlar',
                'order' => 19
            ],
            [
                'name' => 'JBL',
                'slug' => 'jbl',
                'description' => 'Kolonkalar va quloqchinlar',
                'order' => 20
            ],
            [
                'name' => 'Marshall',
                'slug' => 'marshall',
                'description' => 'Premium kolonkalar',
                'order' => 21
            ],
            [
                'name' => 'Beats',
                'slug' => 'beats',
                'description' => 'Quloqchinlar (Apple kompaniyasi)',
                'order' => 22
            ],

            // Smart soatlar
            [
                'name' => 'Garmin',
                'slug' => 'garmin',
                'description' => 'Sport smart soatlar',
                'order' => 23
            ],
            [
                'name' => 'Fitbit',
                'slug' => 'fitbit',
                'description' => 'Fitness trackerlar',
                'order' => 24
            ],

            // Maishiy texnika
            [
                'name' => 'LG',
                'slug' => 'lg',
                'description' => 'Televizorlar va maishiy texnika',
                'order' => 25
            ],
            [
                'name' => 'Bosch',
                'slug' => 'bosch',
                'description' => 'Maishiy texnika',
                'order' => 26
            ],
            [
                'name' => 'Artel',
                'slug' => 'artel',
                'description' => 'O\'zbekiston brendi - televizorlar va texnika',
                'order' => 27
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create(array_merge($brand, ['is_active' => true]));
        }

        $this->command->info('✅ Brendlar yaratildi!');
        $this->command->info('📊 Jami: ' . count($brands) . ' ta brend');
    }
}
