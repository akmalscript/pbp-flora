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
                    <button class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-md">
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
                        <span class="font-medium text-gray-800">Standard Courier (JNE)</span>
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
                        <span class="font-medium text-gray-800" id="payment-total">Rp0</span>
                    </p>
                </div>
            </div>

            <!-- RIGHT SECTION -->
            <div>
                <h3 class="font-semibold text-gray-800 mb-3">Order Summary</h3>
                <div class="border rounded-lg bg-white shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <div class="flex justify-between text-xs font-semibold tracking-wide text-gray-500 uppercase">
                            <span>Item</span>
                            <span>Price</span>
                        </div>
                    </div>

                    <ul class="divide-y divide-gray-100">
                        <li class="flex items-center justify-between px-5 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('images/bouquet/tulip.jpg') }}" alt="Tulip Bouquet"
                                    class="h-12 w-12 rounded-md object-cover" />
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Tulip Bouquet</p>
                                    <p class="text-xs text-gray-500">Hand-crafted seasonal arrangement</p>
                                </div>
                            </div>
                            <span class="item-price text-sm font-medium text-gray-800" data-amount="250000"></span>
                        </li>
                    </ul>

                    <div class="px-5 py-4 border-t border-gray-200 flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span id="items-subtotal" class="font-medium text-gray-800"></span>
                    </div>

                    <div class="px-5 py-4 space-y-2 border-t border-gray-200 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Taxes</span>
                            <span id="tax-amount" class="font-medium text-gray-800" data-amount="0"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping Estimate</span>
                            <span id="shipping-fee" class="font-medium text-gray-800" data-amount="20000"></span>
                        </div>
                    </div>

                    <div
                        class="px-5 py-4 border-t border-gray-200 flex justify-between items-center font-semibold text-gray-900">
                        <span>Estimated Total</span>
                        <span id="estimated-total"></span>
                    </div>

                    <div class="px-5 py-4 border-t border-gray-200 flex flex-col sm:flex-row gap-3">
                        <button class="w-full px-4 py-2 border border-gray-300 text-gray-800 rounded-md hover:bg-gray-100">
                            Download Invoice
                        </button>
                        <button class="w-full px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800">
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
        (() => {
            const formatter = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                maximumFractionDigits: 0
            });

            let subtotal = 0;
            document.querySelectorAll(".item-price").forEach((priceEl) => {
                const amount = parseInt(priceEl.dataset.amount, 10) || 0;
                subtotal += amount;
                priceEl.textContent = formatter.format(amount);
            });

            const taxEl = document.getElementById("tax-amount");
            const shippingEl = document.getElementById("shipping-fee");
            const subtotalEl = document.getElementById("items-subtotal");
            const totalEl = document.getElementById("estimated-total");
            const paymentEl = document.getElementById("payment-total");

            const tax = parseInt(taxEl.dataset.amount, 10) || 0;
            const shipping = parseInt(shippingEl.dataset.amount, 10) || 0;
            const estimatedTotal = subtotal + tax + shipping;

            subtotalEl.textContent = formatter.format(subtotal);
            taxEl.textContent = formatter.format(tax);
            shippingEl.textContent = formatter.format(shipping);
            totalEl.textContent = formatter.format(estimatedTotal);
            paymentEl.textContent = formatter.format(estimatedTotal);
        })();
    </script>
@endpush
