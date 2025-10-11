@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="flex-grow max-w-7xl mx-auto px-6 py-12">
      <h2 class="text-3xl font-bold mb-8 text-gray-800">Order Details</h2>

      <div class="grid md:grid-cols-2 gap-8">
        <!-- LEFT SECTION -->
        <div class="space-y-6">
          <div>
            <p class="text-sm text-gray-600">
              Order ID:
              <span class="font-medium text-gray-800">#FL003</span>
            </p>
            <p class="text-sm text-gray-600 mb-3">
              Date:
              <span class="font-medium text-gray-800">2025-10-02</span>
            </p>
            <button
              class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-md"
            >
              Delivered
            </button>
          </div>

          <div class="border-t border-gray-300 pt-4">
            <h3 class="font-semibold mb-2 text-gray-800">
              Shipping Information
            </h3>
            <p class="text-sm text-gray-600">
              Name: <span class="font-medium text-gray-800">Calista Sahla</span>
            </p>
            <p class="text-sm text-gray-600">
              Address:
              <span class="font-medium text-gray-800">
                The Alton Apartment, Semarang, Indonesia
              </span>
            </p>
            <p class="text-sm text-gray-600">
              Shipping Method:
              <span class="font-medium text-gray-800"
                >Standard Courier (JNE)</span
              >
            </p>
          </div>

          <div class="border-t border-gray-300 pt-4">
            <h3 class="font-semibold mb-2 text-gray-800">
              Payment Information
            </h3>
            <p class="text-sm text-gray-600">
              Payment Method:
              <span class="font-medium text-gray-800">Bank Transfer (BCA)</span>
            </p>
            <p class="text-sm text-gray-600">
              Payment ID:
              <span class="font-medium text-gray-800">#PMT-FL003</span>
            </p>
            <p class="text-sm text-gray-600">
              Total:
              <span class="font-medium text-gray-800" id="payment-total"
                >Rp250.000</span
              >
            </p>
          </div>
        </div>

        <!-- RIGHT SECTION -->
        <div>
          <h3 class="font-semibold text-gray-800 mb-3">Order Summary</h3>
          <div class="border rounded-lg bg-white shadow-sm">
            <!-- Gambar Produk -->
            <div class="p-4 flex flex-col items-center">
              <img
                src="Flowerbouqet.jpg"
                alt="Flower Bouquet"
                class="rounded-lg w-full max-w-sm object-cover"
              />
              <div class="mt-3 text-center">
                <p class="text-gray-800 font-semibold text-lg">
                  Flower Bouquet
                </p>
                <p class="text-gray-700 text-base">
                  Rp <span id="product-price">250000</span>
                </p>
              </div>
            </div>

            <!-- Detail Harga -->
            <div class="border-t border-gray-200 divide-y divide-gray-100">
              <div class="flex justify-between items-center p-4">
                <p class="text-gray-700">Shipping</p>
                <p class="text-gray-800 font-medium">
                  Rp <span id="shipping-fee">20000</span>
                </p>
              </div>
              <div
                class="flex justify-between items-center p-4 font-semibold"
              >
                <p class="text-gray-900">Total</p>
                <p class="text-gray-900">
                  Rp <span id="total-price">0</span>
                </p>
              </div>
            </div>

            <!-- Tombol -->
            <div class="p-4 flex space-x-3">
              <button
                class="px-4 py-2 border border-gray-400 text-gray-800 rounded-md hover:bg-gray-100"
              >
                Download Invoice
              </button>
              <button
                class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700"
              >
                Return Items
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection


@push('scripts')
    <!-- Script: Hitung Total -->
    <script>
      const price = parseInt(document.getElementById("product-price").textContent);
      const shipping = parseInt(document.getElementById("shipping-fee").textContent);
      const total = price + shipping;

      document.getElementById("product-price").textContent = price.toLocaleString("id-ID");
      document.getElementById("shipping-fee").textContent = shipping.toLocaleString("id-ID");
      document.getElementById("total-price").textContent = total.toLocaleString("id-ID");
      document.getElementById("payment-total").textContent = `Rp${total.toLocaleString("id-ID")}`;
    </script>
@endpush
