@extends('layouts.public')

@section('title', 'Riwayat Pesanan | Flora')

@section('content')
@php
    // If the view is rendered directly (e.g. test route) ensure $orders exists to avoid undefined variable errors
    if (!isset($orders)) {
        $orders = collect();
    }
@endphp
    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-6 py-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Order History</h2>
            <div class="relative">
                <select id="order-filter"
                    class="border border-gray-300 rounded-md py-2 pl-3 pr-10 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option>All Orders</option>
                    <option>Delivered</option>
                    <option>Processing</option>
                    <option>Cancelled</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-sm">
                    <tr>
<<<<<<< HEAD
                        <th class="py-3 px-6 cursor-pointer" data-sort="id">Order ID</th>
                        <th class="py-3 px-6 cursor-pointer" data-sort="date">Date</th>
                        <th class="py-3 px-6 cursor-pointer" data-sort="status">Status</th>
                        <th class="py-3 px-6 cursor-pointer" data-sort="total">Total</th>
                        <th class="py-3 px-6">Action</th>
                    </tr>
                </thead>
                <tbody id="orderHistoryTableBody" data-details-url="{{ route('user.orders.details') }}"></tbody>
            </table>
        </div>
        @push('scripts')
            <script>
                (() => {
                    const storageKey = 'floraOrders';
                    const selectedKey = 'floraSelectedOrderId';
                    const tableBody = document.getElementById('orderHistoryTableBody');
                    const filterSelect = document.getElementById('order-filter');
                    const headerCells = document.querySelectorAll('thead [data-sort]');
                    const detailsUrl = tableBody.dataset.detailsUrl;
=======
                        <th class="py-3 px-6">
                            Order ID
                        </th>
                        <th class="py-3 px-6">
                            Date
                        </th>
                        <th class="py-3 px-6">
                            Status
                        </th>
                        <th class="py-3 px-6">
                            Total
                        </th>
                        <th class="py-3 px-6">Action</th>
                    </tr>
                </thead>
                <tbody id="order-table">
                    @forelse ($orders as $order)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="py-4 px-6 font-medium text-gray-800">#{{ $order->id }}</td>
                            <td class="py-4 px-6 text-gray-700">{{ $order->created_at->format('Y-m-d') }}</td>
                            <td class="py-4 px-6 text-gray-800 font-semibold">{{ ucfirst($order->status) }}</td>
                            <td class="py-4 px-6 text-gray-800">Rp{{ number_format($order->total,0,',','.') }}</td>
                            <td class="py-4 px-6">
                                <a href="{{ route('user.orders.show', $order->id) }}" class="text-orange-500 hover:underline">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-6 px-6 text-center text-gray-600" colspan="5">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($orders, 'links'))
            <div class="mt-4 px-6">
                {{ $orders->links() }}
            </div>
        @endif
    </main>
@endsection
>>>>>>> 4569d386ff552293e0d8a03427f5ec04c193d087

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

<<<<<<< HEAD
                    const currencyFormatter = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        maximumFractionDigits: 0
                    });

                    const toCurrency = (value) => currencyFormatter.format(value);

                    const statusColor = (status) => {
                        switch (status) {
                            case 'Delivered':
                                return 'text-emerald-600';
                            case 'Shipping':
                                return 'text-blue-600';
                            case 'Processing':
                                return 'text-amber-500';
                            case 'Cancelled':
                                return 'text-red-500';
                            default:
                                return 'text-gray-600';
                        }
                    };

                    const computeTotals = (order) => {
                        const subtotal = order.items.reduce((sum, item) => sum + item.amount, 0);
                        const shipping = order.shippingFee || 0;
                        const tax = order.tax || 0;
                        return subtotal + shipping + tax;
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

                    let orders = loadOrders();
                    let currentSort = {
                        key: null,
                        asc: true
                    };

                    const applySort = (data) => {
                        if (!currentSort.key) {
                            return data;
                        }
                        const sorted = [...data];
                        sorted.sort((a, b) => {
                            let valueA;
                            let valueB;

                            if (currentSort.key === 'total') {
                                valueA = computeTotals(a);
                                valueB = computeTotals(b);
                            } else {
                                valueA = a[currentSort.key];
                                valueB = b[currentSort.key];
                            }

                            if (typeof valueA === 'string') {
                                return currentSort.asc ? valueA.localeCompare(valueB) : valueB.localeCompare(
                                valueA);
                            }
                            return currentSort.asc ? valueA - valueB : valueB - valueA;
                        });
                        return sorted;
                    };

                    const filterOrders = (data) => {
                        const filterValue = filterSelect.value || 'All Orders';
                        if (filterValue === 'All Orders') {
                            return data;
                        }
                        return data.filter((order) => order.status === filterValue);
                    };

                    const renderOrders = () => {
                        const filtered = filterOrders(orders);
                        const sorted = applySort(filtered);
                        tableBody.innerHTML = '';

                        sorted.forEach((order) => {
                            const total = computeTotals(order);
                            const row = document.createElement('tr');
                            row.className = 'border-t hover:bg-gray-50 transition-colors';
                            row.innerHTML = `
                            <td class="py-4 px-6 font-semibold text-gray-800">#${order.id}</td>
                            <td class="py-4 px-6 text-gray-600">${order.date}</td>
                            <td class="py-4 px-6 font-semibold ${statusColor(order.status)}">${order.status}</td>
                            <td class="py-4 px-6 text-gray-800">${toCurrency(total)}</td>
                            <td class="py-4 px-6">
                                <a href="${detailsUrl}" data-order-id="${order.id}"
                                    class="text-rose-500 font-semibold text-sm hover:underline">View Details</a>
                            </td>
                        `;
                            tableBody.appendChild(row);
                        });
                    };

                    renderOrders();

                    filterSelect.addEventListener('change', renderOrders);

                    headerCells.forEach((cell) => {
                        cell.addEventListener('click', () => {
                            const key = cell.dataset.sort;
                            if (currentSort.key === key) {
                                currentSort.asc = !currentSort.asc;
                            } else {
                                currentSort = {
                                    key,
                                    asc: true
                                };
                            }
                            renderOrders();
                        });
                    });

                    tableBody.addEventListener('click', (event) => {
                        const link = event.target.closest('a[data-order-id]');
                        if (!link) {
                            return;
                        }
                        const orderId = link.dataset.orderId;
                        localStorage.setItem(selectedKey, orderId);
                    });
                })();
            </script>
        @endpush
        });
        });
        </script>
    @endpush
=======
@push('scripts')
    <!-- Script: Filtering -->
    <script>
        const table = document.getElementById("order-table");
        const filter = document.getElementById("order-filter");

        if (table && filter) {
            filter.addEventListener("change", () => {
                const value = filter.value;
                Array.from(table.rows).forEach((row) => {
                    const statusCell = row.cells[2];

                    if (!statusCell) {
                        row.style.display = "";
                        return;
                    }

                    const status = statusCell.innerText.trim();
                    row.style.display =
                        value === "All Orders" || status === value ? "" : "none";
                });
            });
        }
    </script>
@endpush
>>>>>>> 4569d386ff552293e0d8a03427f5ec04c193d087
