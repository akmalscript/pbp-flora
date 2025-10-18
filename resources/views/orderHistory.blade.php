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
