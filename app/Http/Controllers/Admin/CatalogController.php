<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $productSearch = trim((string) $request->query('product_search', ''));
        $categorySearch = trim((string) $request->query('category_search', ''));

        $products = Product::query()
            ->with(['category'])
            ->when($productSearch !== '', function ($query) use ($productSearch) {
                $like = '%' . $productSearch . '%';
                $query->where('name', 'like', $like);
            })
            ->orderBy('name')
            ->get();

        $categories = Category::query()
            ->withCount('products')
            ->when($categorySearch !== '', function ($query) use ($categorySearch) {
                $like = '%' . $categorySearch . '%';
                $query->where('name', 'like', $like);
            })
            ->orderBy('name')
            ->get();

        return view('manajemen-katalog', [
            'products' => $products,
            'categories' => $categories,
            'productSearch' => $productSearch,
            'categorySearch' => $categorySearch,
        ]);
    }
}
