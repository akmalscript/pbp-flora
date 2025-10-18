<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bunga = Category::where('name', 'Bunga')->first();

        if (! $bunga) {
            return;
        }

        $products = [
            [
                'name' => 'Tulip Bouquet',
                'description' => 'Bunga yang mantap',
                'price' => 50000,
                'available' => 10,
                'image_url' => 'images/bouquet/tulip.jpg',
            ],
            [
                'name' => 'Sunflower Bouquet',
                'description' => 'Bunga yang mantap',
                'price' => 50000,
                'available' => 12,
                'image_url' => 'images/bouquet/sunflower.jpg',
            ],
            [
                'name' => 'Lily Bouquet',
                'description' => 'Rangkaian lily segar dengan aroma lembut.',
                'price' => 50000,
                'available' => 8,
                'image_url' => 'images/bouquet/lily.jpg',
            ],
            [
                'name' => 'Mix Bouquet',
                'description' => 'Kombinasi bunga pilihan untuk setiap momen.',
                'price' => 50000,
                'available' => 15,
                'image_url' => 'images/bouquet/mix.jpg',
            ],
            [
                'name' => 'Orchid Bouquet',
                'description' => 'Anggrek elegan cocok untuk hadiah spesial.',
                'price' => 50000,
                'available' => 6,
                'image_url' => 'images/bouquet/orchid.jpg',
            ],
            [
                'name' => 'Peony Bouquet',
                'description' => 'Peony mewah dengan warna pastel yang lembut.',
                'price' => 50000,
                'available' => 5,
                'image_url' => 'images/bouquet/peony.jpg',
            ],
            [
                'name' => 'Rose Bouquet',
                'description' => 'Rangkaian mawar merah klasik penuh makna.',
                'price' => 50000,
                'available' => 18,
                'image_url' => 'images/bouquet/rose.jpg',
            ],
            [
                'name' => 'Hampers Bunga',
                'description' => 'Paket hampers bunga untuk hadiah spesial.',
                'price' => 75000,
                'available' => 7,
                'image_url' => 'images/gifts/hampers bunga.jpg',
            ],
            [
                'name' => 'Karangan Bunga',
                'description' => 'Karangan bunga elegan untuk berbagai acara.',
                'price' => 80000,
                'available' => 9,
                'image_url' => 'images/gifts/karangan bunga.jpg',
            ],
            [
                'name' => 'Papan Bunga',
                'description' => 'Papan ucapan dengan rangkaian bunga yang indah.',
                'price' => 120000,
                'available' => 4,
                'image_url' => 'images/gifts/papan bunga.jpg',
            ],
            [
                'name' => 'Papan Karangan Bunga',
                'description' => 'Papan karangan bunga untuk momen spesial.',
                'price' => 130000,
                'available' => 3,
                'image_url' => 'images/gifts/papan karangan bunga.jpg',
            ],
            [
                'name' => 'Anggrek Pink',
                'lookup_names' => ['VaseFlowerAnggrekPink'],
                'description' => 'Vas bunga anggrek pink yang elegan.',
                'price' => 110000,
                'available' => 6,
                'image_url' => 'images/vase_flower/VaseFlowerAnggrekPink.jpg',
            ],
            [
                'name' => 'Poppy Warna Warni',
                'lookup_names' => ['VaseFlowerPoppyWarnaWarni'],
                'description' => 'Poppy warna-warni dalam vas kaca modern.',
                'price' => 95000,
                'available' => 7,
                'image_url' => 'images/vase_flower/VaseFlowerPoppyWarnaWarni.jpg',
            ],
            [
                'name' => 'Poppy Biru',
                'lookup_names' => ['VaseFlowerPoppyBiru'],
                'description' => 'Rangkaian poppy biru langka dalam vas.',
                'price' => 98000,
                'available' => 5,
                'image_url' => 'images/vase_flower/VaseFlowerPoppyBiru.jpg',
            ],
            [
                'name' => 'Anthurium Mawar Merah',
                'lookup_names' => ['VaseFlowerAnthuriumMawarMerah'],
                'description' => 'Anthurium dan mawar merah dalam satu vas.',
                'price' => 120000,
                'available' => 4,
                'image_url' => 'images/vase_flower/VaseFlowerAnthuriumMawarMerah.jpg',
            ],
            [
                'name' => 'Anthurium Lily Kuning',
                'lookup_names' => ['VaseFlowerAnthuriumLilyKuning'],
                'description' => 'Anthurium dan lily kuning dalam vas elegan.',
                'price' => 118000,
                'available' => 5,
                'image_url' => 'images/vase_flower/VaseFlowerAnthuriumLilyKuning.jpg',
            ],
        ];

        foreach ($products as $data) {
            $lookupNames = Arr::get($data, 'lookup_names', []);
            unset($data['lookup_names']);

            $product = Product::whereIn('name', array_merge([$data['name']], $lookupNames))->first();

            $attributes = [
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'available' => $data['available'],
                'image_url' => $data['image_url'],
                'pre_order_duration' => 0,
                'category_id' => $bunga->id,
            ];

            if ($product) {
                $product->update($attributes);
            } else {
                $product = Product::create($attributes);
            }

            $gallery = [
                $data['image_url'],
                'images/Bunga 3.jpeg',
            ];

            foreach ($gallery as $url) {
                ProductPhoto::firstOrCreate([
                    'product_id' => $product->id,
                    'url' => $url,
                ]);
            }
        }

        // $electronics = Category::where('name', 'Elektronik')->first();
        // $clothing = Category::where('name', 'Pakaian')->first();
        // $food = Category::where('name', 'Makanan & Minuman')->first();
        // $books = Category::where('name', 'Buku')->first();

        // // Produk Elektronik
        // Product::create([
        //     'name' => 'Laptop Gaming',
        //     'description' => 'Laptop dengan performa tinggi untuk gaming.',
        //     'price' => 15000000,
        //     'available' => 10,
        //     'image_url' => 'https://example.com/images/laptop.jpg',
        //     'pre_order_duration' => 0,
        //     'category_id' => $electronics->id,
        // ]);

        // Product::create([
        //     'name' => 'Smartphone XYZ',
        //     'description' => 'Smartphone terbaru dengan kamera canggih.',
        //     'price' => 5000000,
        //     'available' => 20,
        //     'image_url' => 'https://example.com/images/phone.jpg',
        //     'pre_order_duration' => 0,
        //     'category_id' => $electronics->id,
        // ]);

        // // Produk Pakaian
        // Product::create([
        //     'name' => 'Kaos Polos',
        //     'description' => 'Kaos nyaman untuk sehari-hari.',
        //     'price' => 100000,
        //     'available' => 50,
        //     'image_url' => 'https://example.com/images/kaos.jpg',
        //     'pre_order_duration' => 0,
        //     'category_id' => $clothing->id,
        // ]);

        // // Produk Makanan & Minuman
        // Product::create([
        //     'name' => 'Kopi Luwak',
        //     'description' => 'Kopi premium khas Indonesia.',
        //     'price' => 250000,
        //     'available' => 30,
        //     'image_url' => 'https://example.com/images/kopi.jpg',
        //     'pre_order_duration' => 2, // misal pre-order 2 hari
        //     'category_id' => $food->id,
        // ]);

        // // Produk Buku
        // Product::create([
        //     'name' => 'Novel Best Seller',
        //     'description' => 'Novel fiksi terlaris tahun ini.',
        //     'price' => 120000,
        //     'available' => 15,
        //     'image_url' => 'https://example.com/images/novel.jpg',
        //     'pre_order_duration' => 0,
        //     'category_id' => $books->id,
        // ]);
    }
}
