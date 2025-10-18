<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class CatalogController extends Controller
{
    /**
     * Tampilkan halaman manajemen katalog dengan data produk dan kategori dari database.
     */
    public function index(): View
    {
        $products = Product::query()
            ->with(['category', 'photos'])
            ->orderBy('name')
            ->get();

        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return view('manajemen-katalog', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
