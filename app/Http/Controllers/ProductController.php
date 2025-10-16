<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with('photos')->findOrFail($id);

        $total = Product::where('active', 1)
                        ->where('id', '!=', $product->id)
                        ->count();

        $randomOffset = rand(0, max(0, $total - 4));

        $recommendedProducts = Product::where('active', 1)
                                ->where('id', '!=', $product->id)
                                ->skip($randomOffset)
                                ->take(4)
                                ->get();

        return view('productDetail', compact('product', 'recommendedProducts'));
    }
}
