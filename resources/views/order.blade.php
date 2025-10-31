@extends('layouts.app')

@section('content')
<section class="text-center mt-12 mb-8">
    <h1 class="text-3xl font-bold tracking-wide text-gray-900">ORDER MANAGEMENT</h1>
</section>

<main class="max-w-7xl mx-auto px-6 mb-16 space-y-6">
    @if (session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ __('Terjadi kesalahan saat memperbarui status pesanan.') }}
        </div>
    @endif

    <section class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Pesanan</h2>
            <p class="text-sm text-gray-500">Total pesanan: {{ $orders->total() }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="py-3 px-6 text-center">Order ID</th>
                        <th class="py-3 px-6 text-center">Tanggal</th>
                        <th class="py-3 px-6 text-center">Customer</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-center">Total</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-sm text-gray-700">
                    @forelse ($orders as $order)
                        @php
                            $statusRaw = (string) ($order->status ?? '');
                            $statusLabel = $statusOptions[$statusRaw] ?? \Illuminate\Support\Str::headline($statusRaw ?: 'Unknown');
                            $statusKey = strtolower($statusRaw);
                            $statusClass = match ($statusKey) {
                                'delivered', 'completed' => 'text-emerald-600',
                                'shipping' => 'text-blue-600',
                                'processing', 'pending' => 'text-amber-500',
                                'cancelled' => 'text-red-500',
                                default => 'text-gray-600',
                            };
                        @endphp
                        <tr class="border-t border-gray-200 hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 font-semibold text-gray-800">#{{ $order->id }}</td>
                            <td class="py-4 px-6 text-gray-600">{{ optional($order->created_at)->format('d M Y H:i') ?? '-' }}</td>
                            <td class="py-4 px-6 text-gray-700">{{ $order->user->name ?? 'Guest' }}</td>
                            <td class="py-4 px-6 font-semibold {{ $statusClass }}">{{ $statusLabel }}</td>
                            <td class="py-4 px-6 text-gray-800">{{ 'Rp ' . number_format((int) ($order->total ?? 0), 0, ',', '.') }}</td>
                            <td class="py-4 px-6">
                                <div x-data="{ open: false }" class="flex items-center gap-2">
                                    <button 
                                        @click="open = true" 
                                        class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1.5 text-xs font-semibold text-orange-500 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                        Detail
                                    </button>

                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('patch')
                                        <div class="relative">
                                            <select name="status" class="appearance-none rounded-md border border-gray-300 px-3 pr-10 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                @foreach ($statusOptions as $value => $label)
                                                    <option value="{{ $value }}" @selected($value === $statusRaw)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="inline-flex items-center rounded-md bg-orange-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                            Update
                                        </button>
                                    </form>

                                    <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                        <div @click.away="open = false" class="bg-white rounded-lg w-11/12 md:w-2/3 lg:w-1/2 p-6 relative max-h-[80vh] overflow-y-auto">
                                            <h2 class="text-lg font-semibold mb-4">Order #{{ $order->id }} Details</h2>

                                            <div class="space-y-2">
                                                <p><strong>Status:</strong> {{ $order->status }}</p>
                                                <p><strong>Total:</strong> Rp {{ number_format($order->total,0,',','.') }}</p>
                                                <p><strong>Delivery:</strong> {{ $order->delivery ?? '-' }}</p>
                                                <p><strong>Address:</strong> {{ $order->address ?? '-' }}</p>
                                                <p><strong>Courier:</strong> {{ json_decode($order->courier_service)->service ?? '-' }}</p>
                                                <p><strong>Note:</strong> {{ $order->note ?? '-' }}</p>
                                            </div>

                                            <h3 class="mt-4 font-semibold">Items</h3>
                                            <table class="w-full text-left text-sm mt-2">
                                                <thead class="border-b">
                                                    <tr>
                                                        <th class="pb-2">Product</th>
                                                        <th class="pb-2">Price</th>
                                                        <th class="pb-2">Qty</th>
                                                        <th class="pb-2">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->products as $item)
                                                        <tr class="border-b">
                                                            <td class="py-1">{{ $item->name }}</td>
                                                            <td class="py-1">Rp {{ number_format($item->pivot->price,0,',','.') }}</td>
                                                            <td class="py-1">{{ $item->pivot->quantity }}</td>
                                                            <td class="py-1">Rp {{ number_format($item->pivot->sub_total,0,',','.') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <button @click="open = false" class="mt-4 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 px-6 text-center text-sm text-gray-500">
                                Belum ada pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $orders->links() }}
            </div>
        @endif
    </section>
</main>
@endsection
