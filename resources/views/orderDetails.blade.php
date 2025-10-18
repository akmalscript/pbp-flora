@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="flex-grow max-w-7xl mx-auto px-6 py-12">
        @php
            $products = $order->products ?? collect();
            $subtotal = $products->sum(function ($product) {
                $pivot = $product->pivot;
                $price = $pivot->price ?? $product->price ?? 0;
                $quantity = $pivot->quantity ?? 1;
                return $pivot->sub_total ?? ($price * $quantity);
            });

            $courierData = null;
            if (!empty($order->courier_service)) {
                try { $courierData = json_decode($order->courier_service, true); } catch (\Throwable $e) { $courierData = null; }
            }

            $shippingCost = 0;
            if ($courierData && isset($courierData['cost'][0]['value'])) {
                $shippingCost = (int) $courierData['cost'][0]['value'];
            }
            if (!$order->delivery) {
                $shippingCost = 0;
            }

            $total = (int) ($order->total ?? ($subtotal + $shippingCost));
            $discount = max(0, ($subtotal + $shippingCost) - $total);

            $formatCurrency = function ($value) {
                return 'Rp ' . number_format((int) $value, 0, ',', '.');
            };

            $statusClasses = [
                'paid' => 'bg-green-100 text-green-700',
                'pending' => 'bg-yellow-100 text-yellow-700',
                'cancelled' => 'bg-red-100 text-red-700',
            ];
            $statusClass = $statusClasses[strtolower($order->status ?? '')] ?? 'bg-gray-100 text-gray-700';
        @endphp

        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('user.orders.history') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Kembali ke Riwayat Pesanan
            </a>
            <h2 class="text-3xl font-bold text-gray-800">Detail Pesanan</h2>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <p class="text-sm text-gray-600">
                        Order ID:
                        <span class="font-medium text-gray-800">{{ $order->id }}</span>
                    </p>
                    <p class="text-sm text-gray-600 mb-3">
                        Tanggal:
                        <span class="font-medium text-gray-800">
                            {{ optional($order->created_at)->translatedFormat('d F Y H:i') ?? '-' }}
                        </span>
                    </p>
                    <span class="px-3 py-1 text-sm rounded-md {{ $statusClass }}">
                        {{ ucfirst($order->status ?? 'Unknown') }}
                    </span>
                </div>

                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <h3 class="font-semibold text-gray-800">Informasi Pengiriman</h3>
                    <p class="text-sm text-gray-600">
                        Nama: <span class="font-medium text-gray-800">{{ $user->name }}</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Alamat:
                        <span class="font-medium text-gray-800">
                            {{ $order->address ?: '-' }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Metode Pengiriman:
                        <span class="font-medium text-gray-800">
                            {{ $order->delivery ? 'Kurir' : 'Ambil di Toko' }}
                            @if($courierData && isset($courierData['service']))
                                – {{ $courierData['service'] }}
                                @if(isset($courierData['description'])) ({{ $courierData['description'] }}) @endif
                                @if(isset($courierData['cost'][0]['etd'])) • Estimasi {{ $courierData['cost'][0]['etd'] }} hari @endif
                            @elseif(!empty($order->courier_service) && !$courierData)
                                – {{ $order->courier_service }}
                            @endif
                        </span>
                    </p>
                    @if(!empty($order->note))
                        <p class="text-sm text-gray-600">
                            Catatan:
                            <span class="font-medium text-gray-800">{{ $order->note }}</span>
                        </p>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Ringkasan Pesanan</h3>
                <div class="border rounded-lg bg-white shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <div class="flex justify-between text-xs font-semibold tracking-wide text-gray-500 uppercase">
                            <span>Item</span>
                            <span>Total</span>
                        </div>
                    </div>

                    <ul class="divide-y divide-gray-100">
                        @forelse($products as $product)
                            @php
                                $pivot = $product->pivot;
                                $price = $pivot->price ?? $product->price ?? 0;
                                $quantity = $pivot->quantity ?? 1;
                                $lineTotal = $pivot->sub_total ?? ($price * $quantity);
                            @endphp
                            <li class="flex items-start justify-between px-5 py-4">
                                <div class="flex items-start gap-3 mr-4">
                                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name ?? 'Produk' }}" class="h-12 w-12 rounded object-cover border border-gray-200" />
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $product->name ?? 'Produk' }}</p>
                                        <p class="text-xs text-gray-500">Qty: {{ $quantity }} × {{ $formatCurrency($price) }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-medium text-gray-800">{{ $formatCurrency($lineTotal) }}</span>
                            </li>
                        @empty
                            <li class="px-5 py-6 text-sm text-gray-500">Tidak ada item di pesanan ini.</li>
                        @endforelse
                    </ul>

                    <div class="px-5 py-4 border-t border-gray-200 flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span class="font-medium text-gray-800">{{ $formatCurrency($subtotal) }}</span>
                    </div>

                    <div class="px-5 py-4 border-t border-gray-200 flex justify-between text-sm text-gray-600">
                        <span>Biaya Pengiriman</span>
                        <span class="font-medium text-gray-800">{{ $formatCurrency($shippingCost) }}</span>
                    </div>

                    @if($discount > 0)
                        <div class="px-5 py-4 border-t border-gray-200 flex justify-between text-sm text-gray-600">
                            <span>Diskon</span>
                            <span class="font-medium text-green-600">- {{ $formatCurrency($discount) }}</span>
                        </div>
                    @endif

                    <div class="px-5 py-4 border-t border-gray-200 flex justify-between items-center text-base font-semibold text-gray-900">
                        <span>Total</span>
                        <span>{{ $formatCurrency($total) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
