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
                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="flex items-center justify-end gap-2">
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
