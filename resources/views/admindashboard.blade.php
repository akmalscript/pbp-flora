@extends('layouts.app')

@section('content')
    
    <!-- Page Title -->
    <section class="text-center mt-12 mb-10">
      <h1 class="text-3xl font-bold tracking-wide text-gray-900">ADMIN DASHBOARD</h1>
    </section>

    <!-- Stats Section -->
    <section class="max-w-6xl mx-auto grid md:grid-cols-3 gap-6 mb-10">
      <!-- Sales Summary -->
      <div class="bg-white shadow-sm border border-gray-100 rounded-md p-6">
        <h2 class="text-sm font-semibold text-gray-600 mb-2">SALES SUMMARY</h2>
        <p class="text-3xl font-bold text-green-600">$150,000</p>
        <p class="text-xs text-green-500 mt-1">+15%</p>
      </div>

      <!-- Total Orders -->
      <div class="bg-white shadow-sm border border-gray-100 rounded-md p-6">
        <h2 class="text-sm font-semibold text-gray-600 mb-2">TOTAL ORDERS</h2>
        <p class="text-3xl font-bold text-gray-800">1,250</p>
        <p class="text-xs text-gray-400 mt-1">Pending: 50</p>
      </div>

      <!-- Product Stock -->
      <div class="bg-white shadow-sm border border-gray-100 rounded-md p-6">
        <h2 class="text-sm font-semibold text-gray-600 mb-2">PRODUCT STOCK</h2>
        <p class="text-3xl font-bold text-gray-800">850</p>
        <p class="text-xs text-gray-400 mt-1">Low Stock: 35 items</p>
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
                <th>Date</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <tr>
                <td class="py-3">Lily Bouquet</td>
                <td>Processing</td>
                <td>20K</td>
                <td><button class="bg-green-100 text-green-600 text-xs px-2 py-0.5 rounded">+</button></td>
              </tr>
              <tr>
                <td class="py-3">Mix Bouquet</td>
                <td>Shipped</td>
                <td>45K</td>
                <td><button class="bg-green-100 text-green-600 text-xs px-2 py-0.5 rounded">+</button></td>
              </tr>
              <tr>
                <td class="py-3">Rose Bouquet</td>
                <td>Delivery</td>
                <td>55K</td>
                <td><button class="bg-green-100 text-green-600 text-xs px-2 py-0.5 rounded">+</button></td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
          <h3 class="text-sm font-semibold text-gray-700 mb-3">Top Selling Products</h3>
          <table class="w-full text-sm border-t">
            <thead class="text-gray-600 border-b text-left">
              <tr>
                <th class="py-2">Order Name</th>
                <th>Units Sold</th>
                <th>Revenue</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <tr>
                <td class="py-3">Lily Bouquet</td>
                <td>Shipped</td>
                <td>11.9K</td>
              </tr>
              <tr>
                <td class="py-3">Mix Bouquet</td>
                <td>Shipped</td>
                <td>13.0K</td>
              </tr>
              <tr>
                <td class="py-3">Rose Bouquet</td>
                <td>Delivered</td>
                <td>11.6K</td>
              </tr>
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
