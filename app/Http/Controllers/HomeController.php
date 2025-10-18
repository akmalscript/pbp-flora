<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = trim((string) $request->input('search', ''));
        $selectedCategory = $request->input('category', 'all');

        $categoryLabels = [
            'all' => 'All',
            'bouquets' => 'Bouquets',
            'vase-flowers' => 'Vase Flowers',
            'gifts' => 'Gifts',
        ];

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

        $featuredNames = array_values(array_unique(array_merge(
            ...array_values($categoryProductNames)
        )));

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

        if ($selectedCategory !== 'all') {
            $namesToFilter = $categoryProductNames[$selectedCategory] ?? [];

            if (! empty($namesToFilter)) {
                $query->whereIn('name', $namesToFilter);
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

        return view('home', [
            'products' => $filteredProducts,
            'initialProducts' => $initialProducts,
            'featuredNames' => $featuredNames,
            'featuredNamesRemaining' => $remainingFeaturedNames,
            'featuredProducts' => $featuredProducts,
            'featuredImages' => $featuredImages,
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
