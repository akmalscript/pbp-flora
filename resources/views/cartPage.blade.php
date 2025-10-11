@extends('layouts.app')

@section('content')
  <!-- MAIN -->
  <main class="flex-grow max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-3 gap-12">
    <!-- LEFT: Cart Items -->
    <section class="md:col-span-2 space-y-6">
      <h1 class="text-3xl font-bold">Your Cart</h1>

      <div id="cartItems" class="space-y-6"></div>

      <div class="mt-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Gift card or discount code</label>
        <div class="flex gap-3 max-w-md">
          <input id="coupon" type="text" placeholder="Enter code" class="w-full px-4 py-2 border rounded-lg focus:outline-none" />
          <button id="applyCouponBtn" class="bg-gray-900 text-white px-4 rounded-lg hover:bg-gray-800">Apply</button>
        </div>
        <p id="couponMessage" class="text-sm text-green-600 mt-2 hidden"></p>
      </div>

      <div class="mt-6">
        <a href="#" class="inline-flex items-center gap-2 text-sm text-orange-500 hover:underline">
          ← Continue shopping
        </a>
      </div>
    </section>

    <!-- RIGHT: Order Summary -->
    <aside class="md:col-span-1">
      <div class="bg-white rounded-lg shadow p-6 sticky top-24">
        <h2 class="text-lg font-semibold mb-4">Order Summary</h2>

        <div class="flex justify-between text-sm text-gray-600 mb-2">
          <div>Subtotal</div>
          <div id="summarySubtotal">Rp0</div>
        </div>
        <div class="flex justify-between text-sm text-gray-600 mb-2">
          <div>Discount</div>
          <div id="summaryDiscount">Rp0</div>
        </div>
        <div class="flex justify-between text-sm text-gray-600 mb-2">
          <div>Shipping estimate</div>
          <div id="summaryShipping">Rp0</div>
        </div>
        <div class="flex justify-between text-sm text-gray-600 mb-4">
          <div>Taxes (10%)</div>
          <div id="summaryTaxes">Rp0</div>
        </div>

        <div class="grid grid-cols-2 items-baseline mb-4">
            <div class="text-sm font-medium">Estimated Total</div>
            <div id="summaryTotal" class="text-xl font-semibold text-gray-900 text-right tracking-wide">Rp 0</div>
        </div>

        <button id="checkoutBtn" class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition">
          Checkout
        </button>
      </div>
    </aside>
  </main>
@endsection

@push('scripts')
  <!-- SCRIPT -->
  <script>
    let cart = [
      { id: 1, name: "Mini Vase Flower", price: 200000, img: "vaseFlower2.jpg", qty: 1 },
      { id: 2, name: "Flower Bouquet", price: 250000, img: "Flowerbouqet.jpg", qty: 2 },
      { id: 3, name: "Bloom Bag Flower", price: 100000, img: "BloomBag.jpg", qty: 1 }
    ];
    let appliedCoupon = null;

    const fmt = (v) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(v);

    function renderCart() {
      const container = document.getElementById('cartItems');
      if (!container) return;
      if (cart.length === 0) {
        container.innerHTML = `<div class="p-6 border rounded-lg text-center text-gray-600">Your cart is empty.</div>`;
        updateSummary();
        return;
      }

      container.innerHTML = cart.map((item) => `
        <div class="flex items-start justify-between border-b pb-6">
          <div class="flex items-start gap-4">
            <img src="${item.img}" onerror="this.src='https://source.unsplash.com/100x100/?flowers'" class="w-20 h-20 rounded-md object-cover" />
            <div>
              <p class="font-semibold text-lg">${item.name}</p>
              <p class="text-gray-500 text-sm">${fmt(item.price)}</p>
            </div>
          </div>

          <div class="flex flex-col items-end gap-3">
            <div class="flex items-center border rounded-lg">
              <button onclick="decreaseQty(${item.id})" class="px-3 py-2 hover:bg-gray-100">−</button>
              <input type="text" value="${item.qty}" readonly class="w-12 text-center border-x outline-none" />
              <button onclick="increaseQty(${item.id})" class="px-3 py-2 hover:bg-gray-100">+</button>
            </div>
            <div class="flex items-center gap-4 text-sm">
              <button onclick="removeItem(${item.id})" class="text-red-600 hover:underline">Remove</button>
              <div class="text-sm text-gray-700 font-medium">${fmt(item.price * item.qty)}</div>
            </div>
          </div>
        </div>
      `).join('');

      updateSummary();
    }

    function increaseQty(id) { const it = cart.find(x => x.id === id); if (!it) return; it.qty++; renderCart(); }
    function decreaseQty(id) { const it = cart.find(x => x.id === id); if (!it) return; if (it.qty > 1) it.qty--; renderCart(); }
    function removeItem(id) { cart = cart.filter(x => x.id !== id); renderCart(); }

    function updateSummary() {
      const subtotal = cart.reduce((s, i) => s + (i.price * i.qty), 0);
      const shipping = subtotal > 0 ? 20000 : 0;
      const taxes = Math.round(subtotal * 0.10);
      let discount = 0;
      if (appliedCoupon && appliedCoupon.type === 'percent') discount = Math.round(subtotal * (appliedCoupon.value / 100));
      else if (appliedCoupon && appliedCoupon.type === 'fixed') discount = appliedCoupon.value;

      const total = subtotal - discount + shipping + taxes;
      document.getElementById('summarySubtotal').textContent = fmt(subtotal);
      document.getElementById('summaryShipping').textContent = fmt(shipping);
      document.getElementById('summaryTaxes').textContent = fmt(taxes);
      document.getElementById('summaryDiscount').textContent = discount > 0 ? `-${fmt(discount)}` : fmt(0);
      document.getElementById('summaryTotal').textContent = fmt(total);
    }

    document.getElementById('applyCouponBtn').addEventListener('click', () => {
      const code = (document.getElementById('coupon').value || '').trim().toUpperCase();
      const msgEl = document.getElementById('couponMessage');
      if (!code) {
        msgEl.classList.remove('text-green-600'); msgEl.classList.add('text-red-600');
        msgEl.textContent = 'Please enter a code.'; msgEl.classList.remove('hidden'); return;
      }
      if (code === 'FLORA10') {
        appliedCoupon = { code: 'FLORA10', type: 'percent', value: 10 };
        msgEl.classList.remove('text-red-600', 'hidden'); msgEl.classList.add('text-green-600');
        msgEl.textContent = 'Coupon applied: 10% off';
      } else {
        appliedCoupon = null;
        msgEl.classList.remove('text-green-600', 'hidden'); msgEl.classList.add('text-red-600');
        msgEl.textContent = 'Coupon not recognized.';
      }
      renderCart();
    });

    document.getElementById('cartBtn').addEventListener('click', () => alert('Cart opened (dummy action).'));
    document.getElementById('checkoutBtn').addEventListener('click', () => alert(`Proceeding to checkout — Estimated total: ${document.getElementById('summaryTotal').textContent}`));
    renderCart();
  </script>
@endpush

