@extends('layouts.app')

@section('content')
    @php
  $summary = $salesSummary ?? ['totalRevenue' => 0, 'ordersCount' => 0, 'growthPercentage' => null];
  $orders = $orderStats ?? ['total' => 0];
  $inventory = $inventory ?? ['totalStock' => 0, 'lowStockCount' => 0, 'lowStockThreshold' => 0];
  $recentOrders = $recentOrders ?? collect();
  $topSellingProducts = $topSellingProducts ?? collect();
        $totalRevenueFormatted = 'Rp ' . number_format($summary['totalRevenue'], 0, ',', '.');
        $ordersCountFormatted = number_format($summary['ordersCount'], 0, ',', '.');
  $totalOrdersFormatted = number_format($orders['total'], 0, ',', '.');
  $totalStockFormatted = number_format($inventory['totalStock'], 0, ',', '.');
  $lowStockThreshold = $inventory['lowStockThreshold'];
  $lowStockCountFormatted = number_format($inventory['lowStockCount'], 0, ',', '.');
        $growth = $summary['growthPercentage'];
        $growthClass = $growth === null ? 'text-gray-500' : ($growth > 0 ? 'text-green-500' : ($growth < 0 ? 'text-red-500' : 'text-gray-500'));
        $growthText = $growth === null
            ? 'Belum ada perbandingan'
            : (($growth > 0 ? '+' : '') . number_format($growth, 1, ',', '.') . '% vs bulan lalu');
    @endphp

    <!-- Page Title -->
    <section class="text-center mt-12 mb-10">
      <h1 class="text-3xl font-bold tracking-wide text-gray-900">ADMIN DASHBOARD</h1>
    </section>

    <!-- Stats Section -->
    <section class="max-w-6xl mx-auto grid md:grid-cols-3 gap-6 mb-10">
      <!-- Sales Summary -->
      <div class="bg-white shadow-sm border border-gray-100 rounded-md p-6">
        <h2 class="text-sm font-semibold text-gray-600 mb-2">SALES SUMMARY</h2>
        <p class="text-3xl font-bold text-green-600">{{ $totalRevenueFormatted }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $ordersCountFormatted }} pesanan selesai</p>
        <p class="text-xs {{ $growthClass }} mt-1">{{ $growthText }}</p>
      </div>

      <!-- Total Orders -->
      <div class="bg-white shadow-sm border border-gray-100 rounded-md p-6">
        <h2 class="text-sm font-semibold text-gray-600 mb-2">TOTAL ORDERS</h2>
        <p class="text-3xl font-bold text-gray-800">{{ $totalOrdersFormatted }}</p>
      </div>

      <!-- Product Stock -->
      <div class="bg-white shadow-sm border border-gray-100 rounded-md p-6">
        <h2 class="text-sm font-semibold text-gray-600 mb-2">PRODUCT STOCK</h2>
        <p class="text-3xl font-bold text-gray-800">{{ $totalStockFormatted }}</p>
        <p class="text-xs text-gray-400 mt-1">Stok ≤ {{ $lowStockThreshold }}: {{ $lowStockCountFormatted }} produk</p>
      </div>
    </section>

    <!-- Quick Overview -->
    <section class="max-w-6xl mx-auto mb-16">
      <h2 class="text-lg font-semibold mb-6 tracking-wide">QUICK OVERVIEW</h2>

      <div class="grid md:grid-cols-2 gap-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent Orders</h3>
          <table class="w-full text-sm border-t">
            <thead class="text-gray-600 border-b text-left">
              <tr>
                <th class="py-2">Order ID</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              @forelse ($recentOrders as $order)
                <tr>
                  <td class="py-3 font-medium text-gray-800">{{ $order->id }}</td>
                  <td>{{ $order->created_at?->format('d M Y') }}</td>
                  <td class="capitalize">{{ $order->status ?? 'unknown' }}</td>
                  <td>{{ 'Rp ' . number_format($order->total ?? 0, 0, ',', '.') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="py-4 text-center text-gray-400">Belum ada pesanan terbaru.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
          <div class="mt-4 text-right">
            <a href="{{ route('admin.orders.manage') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-green-600 hover:text-green-700">
              Lihat semua pesanan
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M12.293 3.293a1 1 0 011.414 0l4.999 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L15.586 10H2a1 1 0 110-2h13.586l-3.293-3.293a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </a>
          </div>
        </div>

        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Top Selling Products</h3>
          <table class="w-full text-sm border-t">
            <thead class="text-gray-600 border-b text-left">
              <tr>
                <th class="py-2">Produk</th>
                <th>Qty Terjual</th>
                <th>Pendapatan</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              @forelse ($topSellingProducts as $product)
                <tr>
                  <td class="py-3 font-medium text-gray-800">{{ $product->product_name ?? 'Produk Tidak Dikenal' }}</td>
                  <td>{{ number_format($product->total_quantity ?? 0, 0, ',', '.') }}</td>
                  <td>{{ 'Rp ' . number_format($product->total_revenue ?? 0, 0, ',', '.') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="py-4 text-center text-gray-400">Belum ada data penjualan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </section>
@endsection

@push('scripts')

    <!-- Chart.js Script -->
    <script>
      // Sales Line Chart
      const ctx = document.getElementById('salesChart');
      if (ctx) ctx.remove();

      // Category Pie Chart
      const ctx2 = document.getElementById('categoryChart');
      if (ctx2) ctx2.remove();
    </script>
 @endpush
