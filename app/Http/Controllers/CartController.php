<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Dapatkan cart aktif user, buat baru kalau belum ada
    public function getActiveCart()
    {
        $user = Auth::user();

        $cart = Cart::where('user_id', $user->id)
                    ->where('active', true)
                    ->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $user->id,
            ]);
        }

        return $cart;
    }

    // Tampilkan cart
    public function index()
    {
        $cart = $this->getActiveCart();
        $cart->load('products');

        return view('cartPage', compact('cart'));
    }

    // Tambah item ke cart
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getActiveCart();

        $productId = $request->product_id;
        $quantity = $request->quantity;

        if ($cart->products()->where('product_id', $productId)->exists()) {
            $currentQuantity = $cart->products()->where('product_id', $productId)->first()->pivot->quantity;
            $cart->products()->updateExistingPivot($productId, [
                'quantity' => $currentQuantity + $quantity
            ]);
        } else {
            $cart->products()->attach($productId, ['quantity' => $quantity]);
        }

        return redirect()->back()->with('success', "$quantity item(s) added to cart!");
    }

    // Update quantity item
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::findOrFail($itemId);
        $item->quantity = $request->quantity;
        $item->save();

        return response()->json([
            'message' => 'Cart item updated',
            'item' => $item
        ]);
    }

    // Increase quantity
    public function increase($productId)
    {
        $cart = $this->getActiveCart();
        $cartItem = $cart->products()->where('product_id', $productId)->first();

        if (! $cartItem) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        $product = Product::find($productId);

        if (! $product || (int) $product->stock <= 0) {
            $cart->products()->detach($productId);

            return redirect()->back()->with('error', 'Produk sudah tidak tersedia. Item dihapus dari keranjang.');
        }

        $currentQuantity = (int) ($cartItem->pivot->quantity ?? 0);
        $availableStock = (int) $product->stock;

        if ($currentQuantity >= $availableStock) {
            if ($currentQuantity > $availableStock) {
                $cart->products()->updateExistingPivot($productId, [
                    'quantity' => $availableStock,
                ]);

                return redirect()->back()->with('warning', 'Jumlah produk di keranjang disesuaikan dengan stok tersedia.');
            }

            return redirect()->back()->with('warning', 'Jumlah produk di keranjang sudah mencapai stok tersedia.');
        }

        $cart->products()->updateExistingPivot($productId, [
            'quantity' => $currentQuantity + 1,
        ]);

        return redirect()->back()->with('success', 'Quantity increased');
    }

    // Decrease quantity
    public function decrease($productId)
    {
        $cart = $this->getActiveCart();
        $cartItem = $cart->products()->where('product_id', $productId)->first();

        if (! $cartItem) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        $product = Product::find($productId);
        $currentQuantity = (int) ($cartItem->pivot->quantity ?? 0);

        if (! $product || (int) $product->stock <= 0) {
            $cart->products()->detach($productId);

            return redirect()->back()->with('warning', 'Produk dihapus dari keranjang karena stok habis.');
        }

        if ($currentQuantity <= 1) {
            $cart->products()->detach($productId);

            return redirect()->back()->with('success', 'Item removed from cart');
        }

        $availableStock = (int) $product->stock;
        $newQuantity = max(1, min($currentQuantity - 1, $availableStock));

        if ($newQuantity < 1) {
            $cart->products()->detach($productId);

            return redirect()->back()->with('success', 'Item removed from cart');
        }

        $cart->products()->updateExistingPivot($productId, [
            'quantity' => $newQuantity,
        ]);

        $message = $newQuantity < $currentQuantity - 1
            ? 'Kuantitas produk disesuaikan dengan stok tersedia.'
            : 'Quantity updated';

        return redirect()->back()->with('success', $message);
    }

    // Hapus item dari cart
    public function destroy($productId)
    {
        $cart = $this->getActiveCart();
        $cart->products()->detach($productId);

        return redirect()->back()->with('success', 'Item removed from cart');
    }

    // Hapus semua cart (opsional)
    public function clear()
    {
        $cart = $this->getActiveCart();
        $cart->items()->delete();

        return response()->json([
            'message' => 'Cart cleared'
        ]);
    }

    // Menonaktifkan cart (misal checkout)
    public function deactivate($cartId)
    {
        $cart = Cart::findOrFail($cartId);
        $cart->active = false;
        $cart->save();

        return response()->json([
            'message' => 'Cart deactivated'
        ]);
    }

    
}
