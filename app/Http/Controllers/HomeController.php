<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = trim((string) $request->input('search', ''));
        $selectedCategory = $request->input('category', 'all');

        $categoriesFromDb = Category::orderBy('name')->get();
        $categorySlugToName = $categoriesFromDb
            ->mapWithKeys(fn (Category $category) => [Str::slug($category->name) => $category->name]);

        $categoryLabels = collect(['all' => 'All'])
            ->merge($categorySlugToName)
            ->all();

        if (! array_key_exists($selectedCategory, $categoryLabels)) {
            $selectedCategory = 'all';
        }

        $categoryProductNames = [
            'bouquets' => [
                'Tulip Bouquet',
                'Sunflower Bouquet',
                'Lily Bouquet',
                'Mix Bouquet',
                'Orchid Bouquet',
                'Peony Bouquet',
                'Rose Bouquet',
            ],
            'vase-flowers' => [
                'Anggrek Pink',
                'Poppy Warna Warni',
                'Poppy Biru',
                'Anthurium Mawar Merah',
                'Anthurium Lily Kuning',
            ],
            'gifts' => [
                'Hampers Bunga',
                'Karangan Bunga',
                'Papan Bunga',
                'Papan Karangan Bunga',
            ],
        ];

        $categoryProductNames = collect($categoryProductNames)
            ->only(array_keys($categorySlugToName->all()))
            ->toArray();

        $featuredNames = empty($categoryProductNames)
            ? []
            : array_values(array_unique(array_merge(...array_values($categoryProductNames))));

        $featuredImages = [
            'Tulip Bouquet' => 'images/bouquet/tulip.jpg',
            'Sunflower Bouquet' => 'images/bouquet/sunflower.jpg',
            'Lily Bouquet' => 'images/bouquet/lily.jpg',
            'Mix Bouquet' => 'images/bouquet/mix.jpg',
            'Orchid Bouquet' => 'images/bouquet/orchid.jpg',
            'Peony Bouquet' => 'images/bouquet/peony.jpg',
            'Rose Bouquet' => 'images/bouquet/rose.jpg',
            'Hampers Bunga' => 'images/gifts/hampers bunga.jpg',
            'Karangan Bunga' => 'images/gifts/karangan bunga.jpg',
            'Papan Bunga' => 'images/gifts/papan bunga.jpg',
            'Papan Karangan Bunga' => 'images/gifts/papan karangan bunga.jpg',
            'Anggrek Pink' => 'images/vase_flower/VaseFlowerAnggrekPink.jpg',
            'Poppy Warna Warni' => 'images/vase_flower/VaseFlowerPoppyWarnaWarni.jpg',
            'Poppy Biru' => 'images/vase_flower/VaseFlowerPoppyBiru.jpg',
            'Anthurium Mawar Merah' => 'images/vase_flower/VaseFlowerAnthuriumMawarMerah.jpg',
            'Anthurium Lily Kuning' => 'images/vase_flower/VaseFlowerAnthuriumLilyKuning.jpg',
        ];

        $query = Product::query()->where('active', 1);

        $categorySlugToId = $categoriesFromDb
            ->mapWithKeys(fn (Category $category) => [Str::slug($category->name) => $category->id]);

        if ($selectedCategory !== 'all') {
            $categoryId = $categorySlugToId[$selectedCategory] ?? null;

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            } else {
                $query->whereRaw('0 = 1');
            }
        }

        if ($searchTerm !== '') {
            $query->where(function ($builder) use ($searchTerm) {
                $builder->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $filteredProducts = $query->orderBy('name')->get();

        $isSearching = $searchTerm !== '';
        $isFilteringCategory = $selectedCategory !== 'all';

        $initialProducts = ($isSearching || $isFilteringCategory)
            ? $filteredProducts
            : $filteredProducts->take(4);

        $featuredProducts = Product::whereIn('name', $featuredNames)
            ->where('active', 1)
            ->get()
            ->keyBy('name');

        $remainingFeaturedNames = ($isSearching || $isFilteringCategory)
            ? collect()
            : collect($featuredNames)
                ->reject(fn ($name) => $initialProducts->contains('name', $name))
                ->values();

        $hasFeaturedFallback = ! $isSearching && ! $isFilteringCategory && $remainingFeaturedNames->isNotEmpty();

        $resolveImageUrl = static function (?string $path) {
            if (! $path) {
                return null;
            }

            if (Str::startsWith($path, ['http://', 'https://', '//'])) {
                return $path;
            }

            return asset($path);
        };

        $displayProducts = $initialProducts->map(function (Product $product) use ($resolveImageUrl) {
            $stockCount = (int) ($product->stock ?? 0);
            $isOutOfStock = $stockCount <= 0;

            return [
                'name' => $product->name,
                'price' => (int) $product->price,
                'image_url' => $resolveImageUrl($product->image_url),
                'link' => $isOutOfStock ? '#' : route('user.products.show', $product->id),
                'is_clickable' => ! $isOutOfStock,
                'is_out_of_stock' => $isOutOfStock,
                'stock' => $stockCount,
                'show_stock' => true,
                'is_placeholder' => false,
            ];
        });

        if ($hasFeaturedFallback) {
            foreach ($remainingFeaturedNames as $featuredName) {
                $featured = $featuredProducts->get($featuredName);
                $priceValue = (int) ($featured?->price ?? 50000);
                $imagePath = $featured?->image_url ?? ($featuredImages[$featuredName] ?? null);
                $stockCount = $featured ? (int) ($featured->stock ?? 0) : null;
                $isOutOfStock = $featured ? $stockCount <= 0 : false;

                $displayProducts->push([
                    'name' => $featuredName,
                    'price' => $priceValue,
                    'image_url' => $resolveImageUrl($imagePath),
                    'link' => $featured && ! $isOutOfStock ? route('user.products.show', $featured->id) : '#',
                    'is_clickable' => $featured && ! $isOutOfStock,
                    'is_out_of_stock' => $isOutOfStock,
                    'stock' => $featured ? $stockCount : null,
                    'show_stock' => $featured !== null,
                    'is_placeholder' => $featured === null,
                ]);
            }
        }

        $displayProducts = $displayProducts->values();

        return view('home', [
            'displayProducts' => $displayProducts,
            'searchTerm' => $searchTerm,
            'isSearching' => $isSearching,
            'totalResults' => $filteredProducts->count(),
            'categories' => $categoryLabels,
            'selectedCategory' => $selectedCategory,
            'selectedCategoryLabel' => $categoryLabels[$selectedCategory],
            'isFilteringCategory' => $isFilteringCategory,
        ]);
    }
}
