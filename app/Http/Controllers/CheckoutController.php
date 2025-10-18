<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MidtransService;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout.
     */
    public function index()
    {
        // Ambil cart aktif user dan bangun orderItems dari database
        $orderItems = [];

        $user = Auth::user();
        if ($user) {
            $cart = Cart::where('user_id', $user->id)
                        ->where('active', true)
                        ->with('products.photos')
                        ->first();

            if ($cart && $cart->products) {
                foreach ($cart->products as $product) {
                    // Ambil foto utama jika ada, fallback ke product.image_url atau placeholder
                    $imageUrl = null;
                    if ($product->photos && $product->photos->count()) {
                        $imageUrl = $product->photos->first()->url ?? $product->image_url ?? null;
                    } else {
                        $imageUrl = $product->image_url ?? null;
                    }

                    $orderItems[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'quantity' => $product->pivot->quantity ?? 1,
                        'price' => $product->price ?? 0,
                        'imageUrl' => $imageUrl ?? 'https://placehold.co/120x120?text=No+Image',
                    ];
                }
            }
        }

        // Ambil data provinsi untuk pertama kali load
        $provincesResponse = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $provinces = $provincesResponse->successful() ? $provincesResponse->json() : [];

        // Hitung subtotal
        $subtotal = 0;
        foreach ($orderItems as $it) {
            $subtotal += ($it['price'] ?? 0) * ($it['quantity'] ?? 1);
        }

        $discount = session('coupon_discount', 0);

        return view('checkout', [
            'orderItems' => $orderItems,
            'provinces' => $provinces,
            'subtotal' => $subtotal,
            'discount' => $discount,
        ]);
    }

    /**
     * Menghitung ongkos kirim.
     */
    public function calculateShipping(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'city' => 'required',
            'shipping_method' => 'required|string',
            // Tambahkan validasi lain jika perlu
        ]);

        // Jika validasi gagal, kembalikan error sebagai JSON
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $method = $request->input('shipping_method');

        if (strtolower($method) === 'pickup' || strtolower($method) === 'pick up' || strtolower($method) === 'ambil') {
            // Jika ambil di toko, ongkos kirim = 0
            $pickupCost = [
                'service' => 'PICKUP',
                'description' => 'Ambil di Toko (Gratis)',
                'cost' => [['value' => 0, 'etd' => '0']]
            ];

            return response()->json(['cost' => $pickupCost]);
        }

        // --- Logika API Kurir (mock) ---
        // Untuk Kurir kita kembalikan beberapa opsi layanan sebagai array
        $mockServices = [
            [
                'service' => 'REG',
                'description' => 'Layanan Reguler',
                'cost' => [['value' => rand(15000, 35000), 'etd' => '2-3']]
            ],
            [
                'service' => 'YES',
                'description' => 'Yakin Esok Sampai',
                'cost' => [['value' => rand(30000, 60000), 'etd' => '1']]
            ]
        ];

        return response()->json(['cost' => $mockServices]);
    }

    /**
     * Apply coupon code (moved from CartController)
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon' => 'required|string'
        ]);

        $couponCode = strtoupper($request->coupon);

        $validCoupons = [
            'WELCOME' => 10000,
            'FLORA20' => 20000,
            'DISC50K' => 50000,
        ];

        if (array_key_exists($couponCode, $validCoupons)) {
            session(['coupon_discount' => $validCoupons[$couponCode]]);
            $message = 'Coupon applied successfully! Discount: Rp ' . number_format($validCoupons[$couponCode], 0, ',', '.');
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'discount' => $validCoupons[$couponCode],
                    'message' => $message
                ]);
            }

            return redirect()->back()->with('coupon_message', $message);
        }

        if ($request->expectsJson()) {
            return response()->json(['errors' => ['coupon' => ['Invalid coupon code']]], 422);
        }

        return redirect()->back()->withErrors(['coupon' => 'Invalid coupon code']);
    }

    public function payment(Request $request, MidtransService $midtrans)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Build server-side total from cart items, shipping and session coupon
        $cart = Cart::where('user_id', $user->id)->where('active', true)->with('products')->first();
        if (!$cart || $cart->products->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        // subtotal
        $cartSubtotal = 0;
        foreach ($cart->products as $product) {
            $price = $product->price ?? 0;
            $quantity = $product->pivot->quantity ?? 1;
            $cartSubtotal += ($price * $quantity);
        }

        // Determine shipping cost from client's selected courier info if present
        $shippingCost = 0;
        if ($request->filled('selected_courier_service')) {
            $svc = json_decode($request->input('selected_courier_service'), true);
            if (is_array($svc) && isset($svc['cost'][0]['value'])) {
                $shippingCost = (int) $svc['cost'][0]['value'];
            }
        }

        // Read coupon discount from session (if any) and compute final total server-side
        $discount = (int) session('coupon_discount', 0);
        $serverTotal = max(0, (int) $cartSubtotal + (int) $shippingCost - (int) $discount);

        // Create order record (pending) using serverTotal to prevent tampering
        $shippingMethod = $request->input('shipping_method');
        $isDelivery = true;
        if ($shippingMethod && in_array(strtolower($shippingMethod), ['pickup', 'pick up', 'ambil'])) {
            $isDelivery = false;
        }

        $order = Order::create([
            'status' => 'pending',
            'note' => $request->input('note', ''),
            'total' => $serverTotal,
            'delivery' => $isDelivery,
            'address' => $request->input('fullAddress', $request->input('address', '')) ?? '',
            'user_id' => $user->id,
        ]);

        // Attach order items
        foreach ($cart->products as $product) {
            $price = $product->price ?? 0;
            $quantity = $product->pivot->quantity ?? 1;
            $subTotal = $price * $quantity;

            $order->products()->attach($product->id, [
                'price' => $price,
                'quantity' => $quantity,
                'sub_total' => $subTotal,
            ]);
        }

        $orderId = $order->id;

        $customer = [
            'first_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        try {
            // Use serverTotal for payment
            $snapToken = $midtrans->createTransaction($orderId, $serverTotal, $customer);

            return response()->json([
                'snapToken' => $snapToken,
                'orderId' => $orderId,
                'total' => $serverTotal,
            ]);
        } catch (\Exception $e) {
            try { $order->delete(); } catch (\Throwable $t) {}
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Callback / landing after successful payment. Mark order paid and deactivate cart.
     */
    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        if (!$orderId) {
            return redirect()->route('user.checkout.index')->withErrors(['order' => 'Order ID missing']);
        }

        $order = Order::find($orderId);
        if (!$order) {
            return redirect()->route('user.checkout.index')->withErrors(['order' => 'Order not found']);
        }

        $order->status = 'paid';
        $order->save();

        $user = Auth::user();
        $userId = $user ? $user->id : $order->user_id;

        if ($userId) {
            $cart = Cart::where('user_id', $userId)->where('active', true)->first();
            if ($cart) {
                $cart->active = false;
                $cart->save();
            }
        }

    // Redirect to a success page (could be order detail)
    return redirect()->route('user.home');
    }
}