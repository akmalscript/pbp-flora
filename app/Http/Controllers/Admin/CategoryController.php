<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:32'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $category = Category::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Kategori berhasil dibuat.',
                'data' => $category,
            ], 201);
        }

        return redirect()->route('admin.catalog.manage')->with('status', 'Kategori berhasil dibuat.');
    }

    public function update(Request $request, Category $category): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:32'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $category->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Kategori berhasil diperbarui.',
                'data' => $category,
            ]);
        }

        return redirect()->route('admin.catalog.manage')->with('status', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse|JsonResponse
    {
        $categoryName = $category->name;
        $category->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Kategori berhasil dihapus.',
                'data' => ['name' => $categoryName],
            ]);
        }

        return redirect()->route('admin.catalog.manage')->with('status', 'Kategori berhasil dihapus.');
    }
}
