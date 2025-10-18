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
        ]);

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

        // Validate shipping method and ensure courier selection when required
        $selectedCourierPayload = null;
        $validator = Validator::make($request->all(), [
            'shipping_method' => 'required|string',
        ]);

        $validator->after(function ($validator) use ($request, &$selectedCourierPayload) {
            $method = strtolower((string) $request->input('shipping_method', ''));

            if (!in_array($method, ['kurir', 'pickup', 'pick up', 'ambil'], true)) {
                $validator->errors()->add('shipping_method', 'Metode pengiriman tidak valid.');
                return;
            }

            if ($method === 'kurir') {
                $rawCourier = $request->input('selected_courier_service');
                if (empty($rawCourier)) {
                    $validator->errors()->add('selected_courier_service', 'Silakan pilih jasa pengiriman terlebih dahulu.');
                    return;
                }

                if (is_string($rawCourier)) {
                    $decoded = json_decode($rawCourier, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $validator->errors()->add('selected_courier_service', 'Format data jasa pengiriman tidak valid.');
                        return;
                    }
                } elseif (is_array($rawCourier)) {
                    $decoded = $rawCourier;
                } else {
                    $validator->errors()->add('selected_courier_service', 'Data jasa pengiriman tidak valid.');
                    return;
                }

                if (empty($decoded) || !isset($decoded['cost'][0]['value'])) {
                    $validator->errors()->add('selected_courier_service', 'Data jasa pengiriman tidak lengkap.');
                    return;
                }

                $selectedCourierPayload = $decoded;
            }
        });

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'error' => $errors->first() ?? 'Validasi checkout gagal',
                'errors' => $errors,
            ], 422);
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
        if (is_array($selectedCourierPayload) && isset($selectedCourierPayload['cost'][0]['value'])) {
            $shippingCost = (int) $selectedCourierPayload['cost'][0]['value'];
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

        // Normalize note to avoid NULL values
        $note = $request->input('note') ?? '';
        $courierService = null;
        if (is_array($selectedCourierPayload)) {
            $encodedCourier = json_encode($selectedCourierPayload, JSON_UNESCAPED_UNICODE);
            $courierService = $encodedCourier !== false ? $encodedCourier : null;
        }

        // Resolve address parts server-side so we store human-readable names (not numeric ids)
        // Prefer explicit inputs (fullAddress, village, district, city, province, postalCode)
        $fullAddressText = trim((string) $request->input('fullAddress', ''));

        $provinceInput = $request->input('province');
        $cityInput = $request->input('city');
        $districtInput = $request->input('district');
        $villageInput = $request->input('village');
        $provinceLabel = trim((string) $request->input('province_label', ''));
        $cityLabel = trim((string) $request->input('city_label', ''));
        $districtLabel = trim((string) $request->input('district_label', ''));
        $villageLabel = trim((string) $request->input('village_label', ''));
        $postalInput = trim((string) $request->input('postalCode', ''));

        // Try to resolve each region id -> name using external wilayah API; fallback to provided labels
        $provinceName = $this->resolveRegionName('province', $provinceInput) ?? ($provinceLabel !== '' ? $provinceLabel : null);
        $cityName = $this->resolveRegionName('city', $cityInput, $provinceInput) ?? ($cityLabel !== '' ? $cityLabel : null);
        $districtName = $this->resolveRegionName('district', $districtInput, $cityInput) ?? ($districtLabel !== '' ? $districtLabel : null);
        $villageName = $this->resolveRegionName('village', $villageInput, $districtInput) ?? ($villageLabel !== '' ? $villageLabel : null);

        $parts = [];
        if ($fullAddressText !== '') $parts[] = $fullAddressText;
        if ($villageName) $parts[] = $villageName;
        if ($districtName) $parts[] = $districtName;
        if ($cityName) $parts[] = $cityName;
        if ($provinceName) $parts[] = $provinceName;
        if ($postalInput !== '') $parts[] = $postalInput;

        $resolvedAddress = collect($parts)->filter(fn($p) => $p !== null && trim($p) !== '')->implode(', ');

        // If client already sent a concatenated address string and we couldn't build one, fall back
        if ($resolvedAddress === '') {
            $resolvedAddress = $request->input('address', '') ?? '';
        }

        // Create order record (pending) inside try/catch so any DB error is returned as JSON
        try {
            $order = Order::create([
                'status' => 'pending',
                'note' => $note,
                'total' => $serverTotal,
                'delivery' => $isDelivery,
                'address' => $resolvedAddress,
                'courier_service' => $courierService,
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
        } catch (\Throwable $e) {
            // If order was partially created, attempt to delete it
            if (isset($order) && $order instanceof Order) {
                try { $order->delete(); } catch (\Throwable $t) {}
            }

            // Log and return JSON error so client can parse it
            logger()->error('Checkout payment: failed to create order', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to create order: ' . $e->getMessage()], 500);
        }

        $customer = [
            'first_name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        try {
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

        $order->status = 'Processing';
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

        return redirect()->route('user.home');
    }

    private function resolveRegionName(string $type, $input, $parentId = null): ?string
    {
        if (empty($input) && $input !== '0' && $input !== 0) return null;

        if (!is_numeric($input)) {
            return trim((string) $input);
        }

        $base = 'https://www.emsifa.com/api-wilayah-indonesia/api';
        try {
            switch ($type) {
                case 'province':
                    $url = $base . '/provinces.json';
                    $res = Http::get($url);
                    if ($res->successful()) {
                        $list = $res->json();
                        foreach ($list as $p) {
                            if ((string)($p['id'] ?? $p['province_id'] ?? '') === (string)$input) return $p['name'] ?? $p['province'] ?? null;
                        }
                    }
                    break;
                case 'city':
                    if (!$parentId) {
                        $url = $base . '/cities/' . $input . '.json';
                    } else {
                        $url = $base . '/cities/' . $parentId . '.json';
                    }
                    $res = Http::get($url);
                    if ($res->successful()) {
                        $list = $res->json();
                        foreach ($list as $c) {
                            if ((string)($c['id'] ?? $c['city_id'] ?? '') === (string)$input) return $c['name'] ?? $c['city'] ?? null;
                        }
                    }
                    break;
                case 'district':
                    $url = $base . '/districts/' . $parentId . '.json';
                    $res = Http::get($url);
                    if ($res->successful()) {
                        $list = $res->json();
                        foreach ($list as $d) {
                            if ((string)($d['id'] ?? $d['district_id'] ?? '') === (string)$input) return $d['name'] ?? $d['district'] ?? null;
                        }
                    }
                    break;
                case 'village':
                    $url = $base . '/villages/' . $parentId . '.json';
                    $res = Http::get($url);
                    if ($res->successful()) {
                        $list = $res->json();
                        foreach ($list as $v) {
                            if ((string)($v['id'] ?? $v['village_id'] ?? '') === (string)$input) return $v['name'] ?? $v['village'] ?? null;
                        }
                    }
                    break;
                default:
                    return null;
            }
        } catch (\Throwable $e) {
            logger()->debug('resolveRegionName failed', ['type' => $type, 'input' => $input, 'error' => $e->getMessage()]);
        }

        // fallback to returning the raw input as string when it already looks descriptive
        if (is_numeric($input)) {
            return null;
        }

        return trim((string) $input) !== '' ? trim((string) $input) : null;
    }
}