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
            // [
            //     'name' => 'Tulip Bouquet6',
            //     'description' => 'Bright tulips arranged to lift any mood.',
            //     'price' => 50000,
            //     'stock' => 10,
            //     'image_url' => 'images/bouquet/tulip.jpg',
            //     'category_slug' => 'bouquets',
            // ],
            // [
            //     'name' => 'Tulip Bouquet7',
            //     'description' => 'Bright tulips arranged to lift any mood.',
            //     'price' => 50000,
            //     'stock' => 10,
            //     'image_url' => 'images/bouquet/tulip.jpg',
            //     'category_slug' => 'bouquets',
            // ],
            // [
            //     'name' => 'Tulip Bouquet8',
            //     'description' => 'Bright tulips arranged to lift any mood.',
            //     'price' => 50000,
            //     'stock' => 10,
            //     'image_url' => 'images/bouquet/tulip.jpg',
            //     'category_slug' => 'bouquets',
            // ],
            // [
            //     'name' => 'Tulip Bouquet9',
            //     'description' => 'Bright tulips arranged to lift any mood.',
            //     'price' => 50000,
            //     'stock' => 10,
            //     'image_url' => 'images/bouquet/tulip.jpg',
            //     'category_slug' => 'bouquets',
            // ],
            // [
            //     'name' => 'Tulip Bouquet1',
            //     'description' => 'Bright tulips arranged to lift any mood.',
            //     'price' => 50000,
            //     'stock' => 10,
            //     'image_url' => 'images/bouquet/tulip.jpg',
            //     'category_slug' => 'bouquets',
            // ],
            // [
            //     'name' => 'Tulip Bouquet2',
            //     'description' => 'Bright tulips arranged to lift any mood.',
            //     'price' => 50000,
            //     'stock' => 10,
            //     'image_url' => 'images/bouquet/tulip.jpg',
            //     'category_slug' => 'bouquets',
            // ],
            // [
            //     'name' => 'Tulip Bouquet3',
            //     'description' => 'Bright tulips arranged to lift any mood.',
            //     'price' => 50000,
            //     'stock' => 10,
            //     'image_url' => 'images/bouquet/tulip.jpg',
            //     'category_slug' => 'bouquets',
            // ],
            // [
            //     'name' => 'Tulip Bouquet4',
            //     'description' => 'Bright tulips arranged to lift any mood.',
            //     'price' => 50000,
            //     'stock' => 10,
            //     'image_url' => 'images/bouquet/tulip.jpg',
            //     'category_slug' => 'bouquets',
            // ],
            // [
            //     'name' => 'Tulip Bouquet5',
            //     'description' => 'Bright tulips arranged to lift any mood.',
            //     'price' => 50000,
            //     'stock' => 10,
            //     'image_url' => 'images/bouquet/tulip.jpg',
            //     'category_slug' => 'bouquets',
            // ],
            [
                'name' => 'Tulip Bouquet',
                'description' => 'Bright tulips arranged to lift any mood.',
                'price' => 50000,
                'stock' => 10,
                'image_url' => 'images/bouquet/tulip.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Sunflower Bouquet',
                'description' => 'Sunflowers bundled for a sunny celebration.',
                'price' => 50000,
                'stock' => 12,
                'image_url' => 'images/bouquet/sunflower.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Lily Bouquet',
                'description' => 'Fresh lilies with a soft, elegant fragrance.',
                'price' => 50000,
                'stock' => 8,
                'image_url' => 'images/bouquet/lily.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Mix Bouquet',
                'description' => 'Curated mix of blooms suited for any occasion.',
                'price' => 50000,
                'stock' => 15,
                'image_url' => 'images/bouquet/mix.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Orchid Bouquet',
                'description' => 'Elegant orchids perfect for thoughtful gifting.',
                'price' => 50000,
                'stock' => 6,
                'image_url' => 'images/bouquet/orchid.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Peony Bouquet',
                'description' => 'Luxe peonies in soft pastel tones.',
                'price' => 50000,
                'stock' => 5,
                'image_url' => 'images/bouquet/peony.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Rose Bouquet',
                'description' => 'Classic red roses wrapped with heartfelt charm.',
                'price' => 50000,
                'stock' => 18,
                'image_url' => 'images/bouquet/rose.jpg',
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Floral Hamper',
                'description' => 'Gift hamper filled with curated floral delights.',
                'price' => 75000,
                'stock' => 7,
                'image_url' => 'images/gifts/hampers bunga.jpg',
                'category_slug' => 'gifts',
            ],
            [
                'name' => 'Standing Flower Arrangement',
                'description' => 'Tall arrangement designed to elevate any venue.',
                'price' => 80000,
                'stock' => 9,
                'image_url' => 'images/gifts/karangan bunga.jpg',
                'category_slug' => 'gifts',
            ],
            [
                'name' => 'Flower Board',
                'description' => 'Celebratory flower board with custom messaging.',
                'price' => 120000,
                'stock' => 4,
                'image_url' => 'images/gifts/papan bunga.jpg',
                'category_slug' => 'gifts',
            ],
            [
                'name' => 'Grand Flower Board',
                'description' => 'Large-format flower board for milestone events.',
                'price' => 130000,
                'stock' => 3,
                'image_url' => 'images/gifts/papan karangan bunga.jpg',
                'category_slug' => 'gifts',
            ],
            [
                'name' => 'Pink Orchid Vase',
                'lookup_names' => ['VaseFlowerAnggrekPink'],
                'description' => 'Elegant pink orchid set in a glass vase.',
                'price' => 110000,
                'stock' => 6,
                'image_url' => 'images/vase_flower/VaseFlowerAnggrekPink.jpg',
                'category_slug' => 'vase-flowers',
            ],
            [
                'name' => 'Colorful Poppy Vase',
                'lookup_names' => ['VaseFlowerPoppyWarnaWarni'],
                'description' => 'Vibrant poppies displayed in a modern vase.',
                'price' => 95000,
                'stock' => 7,
                'image_url' => 'images/vase_flower/VaseFlowerPoppyWarnaWarni.jpg',
                'category_slug' => 'vase-flowers',
            ],
            [
                'name' => 'Blue Poppy Vase',
                'lookup_names' => ['VaseFlowerPoppyBiru'],
                'description' => 'Rare blue poppies styled in a minimalist vase.',
                'price' => 98000,
                'stock' => 5,
                'image_url' => 'images/vase_flower/VaseFlowerPoppyBiru.jpg',
                'category_slug' => 'vase-flowers',
            ],
            [
                'name' => 'Red Anthurium And Roses',
                'lookup_names' => ['VaseFlowerAnthuriumMawarMerah'],
                'description' => 'Anthuriums paired with red roses in one vase.',
                'price' => 120000,
                'stock' => 4,
                'image_url' => 'images/vase_flower/VaseFlowerAnthuriumMawarMerah.jpg',
                'category_slug' => 'vase-flowers',
            ],
            [
                'name' => 'Yellow Anthurium And Lilies',
                'lookup_names' => ['VaseFlowerAnthuriumLilyKuning'],
                'description' => 'Yellow anthuriums and lilies arranged with ease.',
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
        }
    }
}
