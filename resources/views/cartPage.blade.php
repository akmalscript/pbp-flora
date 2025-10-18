@extends('layouts.app')

@section('content')
<main class="flex-grow max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-3 gap-12">
  <section class="md:col-span-2 space-y-6">
    <h1 class="text-3xl font-bold">Your Cart</h1>

    @if ($cart->products->isEmpty())
      <div class="p-6 border rounded-lg text-center text-gray-600">
        Your cart is empty.
      </div>
    @else
      @foreach ($cart->products as $product)
        @php
          $price = $product->price;
          $subtotalItem = $product->pivot->quantity * $price;
        @endphp
        <div class="flex items-start justify-between border-b pb-6">
          <div class="flex items-start gap-4">
            <img src="{{ asset($product->image_url) }}"\
                 class="w-20 h-20 rounded-md object-cover" />
            <div>
              <p class="font-semibold text-lg">{{ $product->name }}</p>
              <p class="text-gray-500 text-sm">Rp {{ number_format($price, 0, ',', '.') }}</p>
            </div>
          </div>

          <div class="flex flex-col items-end gap-3">
            <div class="flex items-center border rounded-lg">
              <form action="{{ route('user.cart.decrease', $product->id) }}" method="POST">
                @csrf
                <button class="px-3 py-2 hover:bg-gray-100">−</button>
              </form>

              <input type="text" readonly value="{{ $product->pivot->quantity }}"
                     class="w-12 text-center border-x outline-none" />

              <form action="{{ route('user.cart.increase', $product->id) }}" method="POST">
                @csrf
                <button class="px-3 py-2 hover:bg-gray-100">+</button>
              </form>
            </div>

            <div class="flex items-center gap-4 text-sm">
              <form action="{{ route('user.cart.remove', $product->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="text-red-600 hover:underline">Remove</button>
              </form>
              <div class="text-sm text-gray-700 font-medium">
                Rp {{ number_format($subtotalItem, 0, ',', '.') }}
              </div>
            </div>
          </div>
        </div>
      @endforeach
    @endif

    <div class="mt-6">
      <a href="{{ route('user.home') }}"
         class="inline-flex items-center gap-2 text-sm text-orange-500 hover:underline">
        ← Continue shopping
      </a>
    </div>
  </section>

  <aside class="md:col-span-1">
    @php
      $subtotal = $cart->products->sum(fn($product) => $product->pivot->quantity * $product->price);
      $shipping = $subtotal > 0 ? 20000 : 0;
      $discount = session('coupon_discount', 0);
      $total = $subtotal - $discount + $shipping;
    @endphp

    <div class="bg-white rounded-lg shadow p-6 sticky top-24">
      <h2 class="text-lg font-semibold mb-4">Order Summary</h2>

      <div class="flex justify-between text-sm text-gray-600 mb-2">
        <div>Subtotal</div>
        <div>Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
      </div>
      <div class="flex justify-between text-sm text-gray-600 mb-2">
        <div>Discount</div>
        <div>
          {{ $discount > 0 ? '- Rp ' . number_format($discount, 0, ',', '.') : 'Rp 0' }}
        </div>
      </div>
      <div class="flex justify-between text-sm text-gray-600 mb-4">
        <div>Shipping estimate</div>
        <div>Rp {{ number_format($shipping, 0, ',', '.') }}</div>
      </div>

      <div class="grid grid-cols-2 items-baseline mb-4">
        <div class="text-sm font-medium">Estimated Total</div>
        <div class="text-xl font-semibold text-gray-900 text-right tracking-wide">
          Rp {{ number_format($total, 0, ',', '.') }}
        </div>
      </div>

      <form action="{{ route('user.checkout.index') }}" method="GET">
        <button type="submit"
                class="w-full bg-orange-500 text-white py-3 rounded-lg hover:bg-orange-600 transition">
          Checkout
        </button>
      </form>
    </div>
  </aside>
</main>
@endsection
