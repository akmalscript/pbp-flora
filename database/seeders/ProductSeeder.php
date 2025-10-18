<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryMap = Category::all()
            ->keyBy(fn (Category $category) => Str::slug($category->name));

        if ($categoryMap->isEmpty()) {
            return;
        }

        $products = [
            [
                'name' => 'Tulip Bouquet',
                'description' => 'Bunga yang mantap',
                'price' => 50000,
                'stock' => 10,
                'image_url' => 'images/bouquet/tulip.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Sunflower Bouquet',
                'description' => 'Bunga yang mantap',
                'price' => 50000,
                'stock' => 12,
                'image_url' => 'images/bouquet/sunflower.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Lily Bouquet',
                'description' => 'Rangkaian lily segar dengan aroma lembut.',
                'price' => 50000,
                'stock' => 8,
                'image_url' => 'images/bouquet/lily.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Mix Bouquet',
                'description' => 'Kombinasi bunga pilihan untuk setiap momen.',
                'price' => 50000,
                'stock' => 15,
                'image_url' => 'images/bouquet/mix.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Orchid Bouquet',
                'description' => 'Anggrek elegan cocok untuk hadiah spesial.',
                'price' => 50000,
                'stock' => 6,
                'image_url' => 'images/bouquet/orchid.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Peony Bouquet',
                'description' => 'Peony mewah dengan warna pastel yang lembut.',
                'price' => 50000,
                'stock' => 5,
                'image_url' => 'images/bouquet/peony.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Rose Bouquet',
                'description' => 'Rangkaian mawar merah klasik penuh makna.',
                'price' => 50000,
                'stock' => 18,
                'image_url' => 'images/bouquet/rose.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Hampers Bunga',
                'description' => 'Paket hampers bunga untuk hadiah spesial.',
                'price' => 75000,
                'stock' => 7,
                'image_url' => 'images/gifts/hampers bunga.jpg',
                'category_slug' => 'gifts',
            ],
            [
                'name' => 'Karangan Bunga',
                'description' => 'Karangan bunga elegan untuk berbagai acara.',
                'price' => 80000,
                'stock' => 9,
                'image_url' => 'images/gifts/karangan bunga.jpg',
                'category_slug' => 'gifts',
            ],
            [
                'name' => 'Papan Bunga',
                'description' => 'Papan ucapan dengan rangkaian bunga yang indah.',
                'price' => 120000,
                'stock' => 4,
                'image_url' => 'images/gifts/papan bunga.jpg',
                'category_slug' => 'gifts',
            ],
            [
                'name' => 'Papan Karangan Bunga',
                'description' => 'Papan karangan bunga untuk momen spesial.',
                'price' => 130000,
                'stock' => 3,
                'image_url' => 'images/gifts/papan karangan bunga.jpg',
                'category_slug' => 'gifts',
            ],
            [
                'name' => 'Anggrek Pink',
                'lookup_names' => ['VaseFlowerAnggrekPink'],
                'description' => 'Vas bunga anggrek pink yang elegan.',
                'price' => 110000,
                'stock' => 6,
                'image_url' => 'images/vase_flower/VaseFlowerAnggrekPink.jpg',
                'category_slug' => 'vase-flowers',
            ],
            [
                'name' => 'Poppy Warna Warni',
                'lookup_names' => ['VaseFlowerPoppyWarnaWarni'],
                'description' => 'Poppy warna-warni dalam vas kaca modern.',
                'price' => 95000,
                'stock' => 7,
                'image_url' => 'images/vase_flower/VaseFlowerPoppyWarnaWarni.jpg',
                'category_slug' => 'vase-flowers',
            ],
            [
                'name' => 'Poppy Biru',
                'lookup_names' => ['VaseFlowerPoppyBiru'],
                'description' => 'Rangkaian poppy biru langka dalam vas.',
                'price' => 98000,
                'stock' => 5,
                'image_url' => 'images/vase_flower/VaseFlowerPoppyBiru.jpg',
                'category_slug' => 'vase-flowers',
            ],
            [
                'name' => 'Anthurium Mawar Merah',
                'lookup_names' => ['VaseFlowerAnthuriumMawarMerah'],
                'description' => 'Anthurium dan mawar merah dalam satu vas.',
                'price' => 120000,
                'stock' => 4,
                'image_url' => 'images/vase_flower/VaseFlowerAnthuriumMawarMerah.jpg',
                'category_slug' => 'vase-flowers',
            ],
            [
                'name' => 'Anthurium Lily Kuning',
                'lookup_names' => ['VaseFlowerAnthuriumLilyKuning'],
                'description' => 'Anthurium dan lily kuning dalam vas elegan.',
                'price' => 118000,
                'stock' => 5,
                'image_url' => 'images/vase_flower/VaseFlowerAnthuriumLilyKuning.jpg',
                'category_slug' => 'vase-flowers',
            ],
        ];

        foreach ($products as $data) {
            $lookupNames = Arr::get($data, 'lookup_names', []);
            $categorySlug = Arr::pull($data, 'category_slug');
            unset($data['lookup_names']);

            $category = $categorySlug ? $categoryMap->get($categorySlug) : null;

            if (! $category) {
                continue;
            }

            $product = Product::whereIn('name', array_merge([$data['name']], $lookupNames))->first();

            $attributes = [
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'stock' => $data['stock'],
                'image_url' => $data['image_url'],
                'pre_order_duration' => 0,
                'category_id' => $category->id,
            ];

            if ($product) {
                $product->update($attributes);
            } else {
                $product = Product::create($attributes);
            }

            // Additional gallery photos removed; rely on primary image_url only.
        }

        // $electronics = Category::where('name', 'Elektronik')->first();
        // $clothing = Category::where('name', 'Pakaian')->first();
        // $food = Category::where('name', 'Makanan & Minuman')->first();
        // $books = Category::where('name', 'Buku')->first();

        // // Produk Elektronik
        // Additional sample products removed.
    }
}
