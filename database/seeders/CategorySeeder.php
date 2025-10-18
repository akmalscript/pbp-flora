<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Category::create([
        //     'name' => 'Elektronik',
        //     'description' => 'Perangkat elektronik seperti HP, Laptop, dll.'
        // ]);

        // Category::create([
        //     'name' => 'Pakaian',
        //     'description' => 'Baju, celana, dan aksesoris fashion.'
        // ]);

        // Category::create([
        //     'name' => 'Makanan & Minuman',
        //     'description' => 'Snack, minuman, dan bahan makanan.'
        // ]);

        // Category::create([
        //     'name' => 'Buku',
        //     'description' => 'Buku fiksi, non-fiksi, dan buku anak-anak.'
        // ]);

        $categories = [
            [
                'name' => 'Bouquets',
                'description' => 'Fresh hand-tied bouquets ready for gifting.',
            ],
            [
                'name' => 'Vase Flowers',
                'description' => 'Elegant flower arrangements presented in vases.',
            ],
            [
                'name' => 'Gifts',
                'description' => 'Complementary gifts and hampers to pair with flowers.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
