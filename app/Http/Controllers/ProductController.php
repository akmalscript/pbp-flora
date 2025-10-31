<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Daftar produk untuk admin (opsional, digunakan jika route index dipanggil).
     */
    public function index(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            $products = Product::query()
                ->with('category')
                ->orderBy('name')
                ->paginate(15);

            return response()->json($products);
        }

        return redirect()->route('admin.catalog.manage');
    }

    /**
     * Tampilkan formulir pembuatan produk baru (dialihkan ke halaman katalog).
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('admin.catalog.manage');
    }

    /**
     * Tampilkan data produk untuk diedit (JSON) atau kembali ke katalog.
     */
    public function edit(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json($product->loadMissing('category'));
        }

        return redirect()->route('admin.catalog.manage');
    }

    /**
     * Simpan produk baru.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $data = $this->validateProductData($request);

        $request->validate([
            'image_file' => ['required', 'image', 'max:5120'],
        ]);

        $storedPath = $request->file('image_file')->store('products', 'public');
        $publicPath = ltrim(Storage::url($storedPath), '/');
        $data['image_url'] = $publicPath;

        $product = Product::create($data);

        return $this->respondWithProduct($request, $product, 'Produk berhasil ditambahkan.', 201);
    }

    public function show($id)
    {
    $product = Product::findOrFail($id);

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

    /**
     * Perbarui produk yang ada.
     */
    public function update(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $data = $this->validateProductData($request);

        if ($request->hasFile('image_file')) {
            $request->validate([
                'image_file' => ['image', 'max:5120'],
            ]);

            $storedPath = $request->file('image_file')->store('products', 'public');
            $data['image_url'] = ltrim(Storage::url($storedPath), '/');
        } else {
            $data['image_url'] = $product->image_url;
        }

        $product->update($data);

        return $this->respondWithProduct($request, $product->refresh(), 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk.
     */
    public function destroy(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $product->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Produk berhasil dihapus.'], 200);
        }

        return redirect()->route('admin.catalog.manage')->with('status', 'Produk berhasil dihapus.');
    }

    /**
     * Validasi dan normalisasi data produk.
     */
    protected function validateProductData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:32'],
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'pre_order_duration' => ['required', 'integer', 'min:0'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'active' => ['sometimes', 'boolean'],
        ]);

        $payload = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => (int) $validated['price'],
            'stock' => (int) $validated['stock'],
            'pre_order_duration' => (int) $validated['pre_order_duration'],
            'category_id' => $validated['category_id'] ?? null,
        ];

        if (array_key_exists('active', $validated)) {
            $payload['active'] = $request->boolean('active');
        }

        return $payload;
    }

    /**
     * Kembalikan respon berdasarkan tipe permintaan.
     */
    protected function respondWithProduct(Request $request, Product $product, string $message, int $status = 200): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'data' => $product->loadMissing('category'),
            ], $status);
        }

        return redirect()->route('admin.catalog.manage')->with('status', $message);
    }
}
