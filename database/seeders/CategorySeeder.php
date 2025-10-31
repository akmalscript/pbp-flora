<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
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
            // [
            //     'name' => 'Gifts1',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
            // [
            //     'name' => 'Gifts2',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
            // [
            //     'name' => 'Gifts3',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
            // [
            //     'name' => 'Gifts4',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
            // [
            //     'name' => 'Gifts5',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
            // [
            //     'name' => 'Gifts6',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
            // [
            //     'name' => 'Gifts7',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
            // [
            //     'name' => 'Gifts8',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
            // [
            //     'name' => 'Gifts9',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
            // [
            //     'name' => 'Gifts10',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
            // [
            //     'name' => 'Gifts11',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
            // [
            //     'name' => 'Gifts12',
            //     'description' => 'Complementary gifts and hampers to pair with flowers.',
            // ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
