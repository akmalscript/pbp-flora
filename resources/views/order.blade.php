@extends('layouts.app')

@section('content')
    <!-- Page Title -->
    <section class="text-center mt-12 mb-8">
        <h1 class="text-3xl font-bold tracking-wide text-gray-900">ORDER MANAGEMENT</h1>
    </section>

    <main class="max-w-7xl mx-auto px-6 mb-16">
        <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Order Management</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="py-3 px-6">Order ID</th>
                            <th class="py-3 px-6">Date</th>
                            <th class="py-3 px-6">Status</th>
                            <th class="py-3 px-6">Total</th>
                            <th class="py-3 px-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody" class="bg-white text-sm text-gray-700"></tbody>
                </table>
            </div>
        </section>
    </main>

    <div id="statusModal" class="fixed inset-0 z-40 hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">Update Order Status</h3>
                <button type="button" id="closeStatusModal"
                    class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">✕</button>
            </div>
            <div class="px-5 py-5 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Order</p>
                    <p id="modalOrderId" class="text-lg font-semibold text-gray-800"></p>
                </div>
                <label class="text-sm text-gray-600 block" for="statusSelect">Status</label>
                <select id="statusSelect"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-300">
                </select>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" id="cancelStatusUpdate"
                    class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100">Cancel</button>
                <button type="button" id="confirmStatusUpdate"
                    class="px-4 py-2 text-sm rounded-lg bg-rose-500 text-white hover:bg-rose-600">Save</button>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        (() => {
            const storageKey = 'floraOrders';
            const statusOptions = ['Processing', 'Shipping', 'Delivered', 'Cancelled'];
            const ordersTableBody = document.getElementById('ordersTableBody');
            const modal = document.getElementById('statusModal');
            const statusSelect = document.getElementById('statusSelect');
            const modalOrderIdText = document.getElementById('modalOrderId');
            const closeModalButtons = [
                document.getElementById('closeStatusModal'),
                document.getElementById('cancelStatusUpdate')
            ];
            const confirmButton = document.getElementById('confirmStatusUpdate');

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

            let orders = loadOrders();

            const persistOrders = () => {
                localStorage.setItem(storageKey, JSON.stringify(orders));
            };

            const ensureStatusOptions = () => {
                statusSelect.innerHTML = '';
                statusOptions.forEach((status) => {
                    const option = document.createElement('option');
                    option.value = status;
                    option.textContent = status;
                    statusSelect.appendChild(option);
                });
            };

            const renderOrders = () => {
                ordersTableBody.innerHTML = '';
                orders.forEach((order) => {
                    const {
                        total
                    } = computeTotals(order);
                    const row = document.createElement('tr');
                    row.className = 'border-t border-gray-200 hover:bg-gray-50 transition-colors';
                    row.innerHTML = `
                        <td class="py-4 px-6 font-semibold text-gray-800">#${order.id}</td>
                        <td class="py-4 px-6 text-gray-600">${order.date}</td>
                        <td class="py-4 px-6 font-semibold ${statusColor(order.status)}">${order.status}</td>
                        <td class="py-4 px-6 text-gray-800">${toCurrency(total)}</td>
                        <td class="py-4 px-6 text-right">
                            <button type="button" data-edit-id="${order.id}"
                                class="text-rose-500 font-semibold text-sm hover:underline">Edit</button>
                        </td>
                    `;
                    ordersTableBody.appendChild(row);
                });
            };

            ensureStatusOptions();
            renderOrders();

            let editingOrderId = null;

            const openModal = (orderId) => {
                editingOrderId = orderId;
                modalOrderIdText.textContent = `#${orderId}`;
                const order = orders.find((item) => item.id === orderId);
                if (order) {
                    statusSelect.value = order.status;
                }
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                editingOrderId = null;
            };

            ordersTableBody.addEventListener('click', (event) => {
                const trigger = event.target.closest('[data-edit-id]');
                if (!trigger) {
                    return;
                }
                openModal(trigger.dataset.editId);
            });

            confirmButton.addEventListener('click', () => {
                if (!editingOrderId) {
                    return;
                }
                const newStatus = statusSelect.value;
                orders = orders.map((order) => order.id === editingOrderId ? {
                    ...order,
                    status: newStatus
                } : order);
                persistOrders();
                renderOrders();
                closeModal();
            });

            closeModalButtons.forEach((button) => {
                button.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });
        })();
    </script>
@endpush
