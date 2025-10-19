@extends('layouts.public')

@section('title', 'Detail Pesanan | Flora')

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
<<<<<<< HEAD
                        <span id="orderId" class="font-medium text-gray-800">#FL003</span>
                    </p>
                    <p class="text-sm text-gray-600 mb-3">
                        Date:
                        <span id="orderDate" class="font-medium text-gray-800">2025-10-02</span>
                    </p>
                    <span id="orderStatusBadge"
                        class="inline-flex px-3 py-1 text-sm rounded-md font-semibold bg-emerald-100 text-emerald-700">Delivered</span>
=======
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
>>>>>>> 4569d386ff552293e0d8a03427f5ec04c193d087
                </div>

                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <h3 class="font-semibold text-gray-800">Informasi Pengiriman</h3>
                    <p class="text-sm text-gray-600">
<<<<<<< HEAD
                        Name: <span id="customerName" class="font-medium text-gray-800">Calista Sahla</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Address:
                        <span id="customerAddress" class="font-medium text-gray-800">
                            The Alton Apartment, Semarang, Indonesia
                        </span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Shipping Method:
                        <span id="shippingMethod" class="font-medium text-gray-800">Standard Courier (JNE)</span>
                    </p>
                </div>

                <div class="border-t border-gray-300 pt-4">
                    <h3 class="font-semibold mb-2 text-gray-800">
                        Payment Information
                    </h3>
                    <p class="text-sm text-gray-600">
                        Payment Method:
                        <span id="paymentMethod" class="font-medium text-gray-800">Bank Transfer (BCA)</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Payment ID:
                        <span id="paymentId" class="font-medium text-gray-800">#PMT-FL003</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Total:
                        <span class="font-medium text-gray-800" id="paymentTotal">Rp0</span>
=======
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
>>>>>>> 4569d386ff552293e0d8a03427f5ec04c193d087
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

<<<<<<< HEAD
                    <ul id="orderItems" class="divide-y divide-gray-100"></ul>

                    <div class="px-5 py-4 border-t border-gray-200 flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span id="itemsSubtotal" class="font-medium text-gray-800"></span>
                    </div>

                    <div class="px-5 py-4 space-y-2 border-t border-gray-200 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Taxes</span>
                            <span id="taxAmount" class="font-medium text-gray-800"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping Estimate</span>
                            <span id="shippingFee" class="font-medium text-gray-800"></span>
                        </div>
                    </div>

                    <div
                        class="px-5 py-4 border-t border-gray-200 flex justify-between items-center font-semibold text-gray-900">
                        <span>Estimated Total</span>
                        <span id="estimatedTotal"></span>
                    </div>

                    <div class="px-5 py-4 border-t border-gray-200 flex flex-col sm:flex-row gap-3">
                        <button class="w-full px-4 py-2 border border-gray-300 text-gray-800 rounded-md hover:bg-gray-100">
                            Download Invoice
                        </button>
                        <button class="w-full px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800">
                            Return Items
                        </button>
=======
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
>>>>>>> 4569d386ff552293e0d8a03427f5ec04c193d087
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
<<<<<<< HEAD


@push('scripts')
    <script>
        (() => {
            const storageKey = 'floraOrders';
            const selectedKey = 'floraSelectedOrderId';

            const defaultOrders = [{
                    id: 'FL004',
                    date: '2025-10-06',
                    status: 'Shipping',
                    customer: 'Elisa Hartono',
                    address: 'Jl. Ngesrep Barat V No.17, Semarang, Indonesia',
                    shippingMethod: 'Express Courier (JNE)',
                    paymentMethod: 'Bank Transfer (BCA)',
                    paymentId: 'PMT-FL004',
                    tax: 0,
                    shippingFee: 20000,
                    items: [{
                        name: 'Orchid Bloom Box',
                        description: 'Premium orchid arrangement with satin ribbon',
                        amount: 160000,
                        image: "{{ asset('images/bouquet/orchid.jpg') }}"
                    }]
                },
                {
                    id: 'FL003',
                    date: '2025-10-02',
                    status: 'Delivered',
                    customer: 'Calista Sahla',
                    address: 'The Alton Apartment, Semarang, Indonesia',
                    shippingMethod: 'Standard Courier (JNE)',
                    paymentMethod: 'Bank Transfer (BCA)',
                    paymentId: 'PMT-FL003',
                    tax: 0,
                    shippingFee: 20000,
                    items: [{
                        name: 'Tulip Bouquet',
                        description: 'Hand-crafted seasonal arrangement',
                        amount: 230000,
                        image: "{{ asset('images/bouquet/tulip.jpg') }}"
                    }]
                },
                {
                    id: 'FL002',
                    date: '2025-09-29',
                    status: 'Processing',
                    customer: 'Raka Wicaksana',
                    address: 'Cluster Harmoni, Banyumanik, Semarang',
                    shippingMethod: 'Same Day Courier (Gojek)',
                    paymentMethod: 'Midtrans Virtual Account',
                    paymentId: 'PMT-FL002',
                    tax: 0,
                    shippingFee: 15000,
                    items: [{
                            name: 'Sunset Roses',
                            description: 'Bouquet with warm-toned roses and eucalyptus',
                            amount: 55000,
                            image: "{{ asset('images/bouquet/rose.jpg') }}"
                        },
                        {
                            name: 'Pastel Posy',
                            description: 'Seasonal mini bouquet add-on',
                            amount: 25000,
                            image: "{{ asset('images/bouquet/mix.jpg') }}"
                        }
                    ]
                },
                {
                    id: 'FL001',
                    date: '2025-09-20',
                    status: 'Cancelled',
                    customer: 'Nadia Putri',
                    address: 'Jl. Sisingamangaraja No.12, Semarang',
                    shippingMethod: 'Standard Courier (J&T)',
                    paymentMethod: 'Card Payment',
                    paymentId: 'PMT-FL001',
                    tax: 0,
                    shippingFee: 0,
                    items: [{
                        name: 'Peony Basket',
                        description: 'Curated basket with plush peonies',
                        amount: 150000,
                        image: "{{ asset('images/bouquet/peony.jpg') }}"
                    }]
                }
            ];

            const currencyFormatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            });

            const toCurrency = (value) => currencyFormatter.format(value);

            const statusBadgeClass = (status) => {
                switch (status) {
                    case 'Delivered':
                        return 'bg-emerald-100 text-emerald-700';
                    case 'Shipping':
                        return 'bg-blue-100 text-blue-700';
                    case 'Processing':
                        return 'bg-amber-100 text-amber-700';
                    case 'Cancelled':
                        return 'bg-red-100 text-red-700';
                    default:
                        return 'bg-gray-100 text-gray-600';
                }
            };

            const computeTotals = (order) => {
                const subtotal = order.items.reduce((sum, item) => sum + item.amount, 0);
                const shipping = order.shippingFee || 0;
                const tax = order.tax || 0;
                return {
                    subtotal,
                    shipping,
                    tax,
                    total: subtotal + shipping + tax
                };
            };

            const loadOrders = () => {
                try {
                    const stored = localStorage.getItem(storageKey);
                    if (!stored) {
                        localStorage.setItem(storageKey, JSON.stringify(defaultOrders));
                        return [...defaultOrders];
                    }
                    const parsed = JSON.parse(stored);
                    if (!Array.isArray(parsed) || parsed.length === 0) {
                        localStorage.setItem(storageKey, JSON.stringify(defaultOrders));
                        return [...defaultOrders];
                    }
                    return parsed;
                } catch (error) {
                    console.error('Failed to load orders, resetting defaults.', error);
                    localStorage.setItem(storageKey, JSON.stringify(defaultOrders));
                    return [...defaultOrders];
                }
            };

            const orders = loadOrders();
            const selectedId = localStorage.getItem(selectedKey);
            const fallbackOrder = orders[0];
            const currentOrder = orders.find((order) => order.id === selectedId) || fallbackOrder;

            if (!currentOrder) {
                return;
            }

            document.getElementById('orderId').textContent = `#${currentOrder.id}`;
            document.getElementById('orderDate').textContent = currentOrder.date;

            const statusBadge = document.getElementById('orderStatusBadge');
            statusBadge.textContent = currentOrder.status;
            statusBadge.className =
                `inline-flex px-3 py-1 text-sm rounded-md font-semibold ${statusBadgeClass(currentOrder.status)}`;

            document.getElementById('customerName').textContent = currentOrder.customer;
            document.getElementById('customerAddress').textContent = currentOrder.address;
            document.getElementById('shippingMethod').textContent = currentOrder.shippingMethod;
            document.getElementById('paymentMethod').textContent = currentOrder.paymentMethod;
            document.getElementById('paymentId').textContent = `#${currentOrder.paymentId}`;

            const itemsList = document.getElementById('orderItems');
            itemsList.innerHTML = '';
            currentOrder.items.forEach((item) => {
                const listItem = document.createElement('li');
                listItem.className = 'flex items-center justify-between px-5 py-4';
                listItem.innerHTML = `
                    <div class="flex items-center gap-3">
                        <img src="${item.image}" alt="${item.name}" class="h-12 w-12 rounded-md object-cover" />
                        <div>
                            <p class="text-sm font-semibold text-gray-800">${item.name}</p>
                            <p class="text-xs text-gray-500">${item.description}</p>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-gray-800">${toCurrency(item.amount)}</span>
                `;
                itemsList.appendChild(listItem);
            });

            const totals = computeTotals(currentOrder);
            document.getElementById('itemsSubtotal').textContent = toCurrency(totals.subtotal);
            document.getElementById('taxAmount').textContent = toCurrency(totals.tax);
            document.getElementById('shippingFee').textContent = toCurrency(totals.shipping);
            document.getElementById('estimatedTotal').textContent = toCurrency(totals.total);
            document.getElementById('paymentTotal').textContent = toCurrency(totals.total);
        })();
    </script>
@endpush
=======
>>>>>>> 4569d386ff552293e0d8a03427f5ec04c193d087
