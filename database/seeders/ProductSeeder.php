<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bunga = Category::where('name', 'Bunga')->first();
        $product1 = Product::create([
            'name' => 'Tulip Bouquet',
            'description' => 'Bunga yang mantap',
            'price' => 50000,
            'available' => 10,
            'image_url' => 'images/tulip.jpg',
            'pre_order_duration' => 0,
            'category_id' => $bunga->id,
        ]);
        
        $product2 = Product::create([
            'name' => 'Sunflower Bouquet',
            'description' => 'Bunga yang mantap',
            'price' => 50000,
            'available' => 10,
            'image_url' => 'images/sunflower.jpg',
            'pre_order_duration' => 0,
            'category_id' => $bunga->id,
        ]);

        $photos = [
            'images/tulip.jpg',
            'images/sunflower.jpg',
            'images/Bunga 3.jpeg',
        ];

        foreach ($photos as $url) {
            ProductPhoto::create([
                'product_id' => $product1->id,
                'url' => $url,
            ]);

            ProductPhoto::create([
                'product_id' => $product2->id,
                'url' => $url,
            ]);
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
