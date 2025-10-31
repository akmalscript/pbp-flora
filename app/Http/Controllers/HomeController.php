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

        $filteredProducts = $query->orderBy('name')->paginate(14)->withQueryString();;

        $isSearching = $searchTerm !== '';
        $isFilteringCategory = $selectedCategory !== 'all';

        // $initialProducts = $filteredProducts;

        $resolveImageUrl = static function (?string $path) {
            if (! $path) {
                return null;
            }

            if (Str::startsWith($path, ['http://', 'https://', '//'])) {
                return $path;
            }

            return asset($path);
        };

        $displayProducts = collect($filteredProducts->items())->map(function (Product $product) use ($resolveImageUrl) {
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
            'productsPaginator' => $filteredProducts,
        ]);
    }
}
