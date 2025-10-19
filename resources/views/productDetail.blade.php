@extends('layouts.public')

@section('title', ($product->name ?? 'Produk') . ' | Flora')

@section('content')
@php
  $availableStock = max(0, (int) ($product->stock ?? 0));
@endphp

<!-- MAIN CONTENT -->
<div class="max-w-7xl mx-auto px-6 pt-8">
  <button type="button" onclick="window.history.back()"
    class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12l7.5-7.5" />
      <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18" />
    </svg>
    <span>Kembali</span>
  </button>
</div>

<main class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-12 items-start">

  <!-- LEFT: IMAGE GALLERY -->
  <div class="space-y-4">
    <img 
      src="{{ asset($product->image_url) }}" 
      alt="{{ $product->name }}" 
      class="w-full rounded-2xl shadow-lg object-cover" 
    />
  </div>

  <!-- RIGHT: PRODUCT INFO -->
  <div>
    <h1 class="text-4xl font-bold mb-2">{{ $product->name }}</h1>
    <p class="text-gray-500 mb-6 leading-relaxed">{{ $product->description }}</p>

    <!-- PRICE -->
    <div class="flex items-baseline gap-3 mb-6">
      <p class="text-3xl font-semibold text-orange-600">
        Rp{{ number_format($product->price, 0, ',', '.') }}
      </p>
    </div>

    <p class="text-sm font-medium {{ $availableStock > 0 ? 'text-green-600' : 'text-red-500' }} mb-6">
      {{ $availableStock > 0 ? 'Stok: ' . $availableStock : 'Stok habis' }}
    </p>

    <!-- QUANTITY + ADD TO CART -->
    <form action="{{ route('user.cart.store') }}" method="POST">
      @csrf
      <input type="hidden" name="product_id" value="{{ $product->id }}">
      <div class="flex items-center gap-4 mb-8">
        <div class="flex items-center border rounded-lg">
          <button type="button" id="decrease" class="px-4 py-2 text-xl hover:bg-gray-100" {{ $availableStock === 0 ? 'disabled' : '' }}>-</button>
                    <input id="quantity" name="quantity" type="number" value="{{ $availableStock > 0 ? 1 : 0 }}" readonly data-max="{{ $availableStock }}" min="{{ $availableStock > 0 ? 1 : 0 }}" max="{{ $availableStock }}" class="w-12 text-center border-x outline-none text-lg {{ $availableStock === 0 ? 'text-gray-400' : '' }}" />
          <button type="button" id="increase" class="px-4 py-2 text-xl hover:bg-gray-100" {{ $availableStock === 0 ? 'disabled' : '' }}>+</button>
        </div>
        <button type="submit" class="px-8 py-3 rounded-lg text-sm font-medium transition {{ $availableStock === 0 ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-orange-500 text-white hover:bg-orange-600' }}" {{ $availableStock === 0 ? 'disabled' : '' }}>
          {{ $availableStock === 0 ? 'Stok Habis' : 'Add to Cart' }}
        </button>
      </div>
    </form>

  </div>
</main>

<!-- RECOMMENDATION SECTION -->
<section class="max-w-7xl mx-auto px-6 mt-20">
  <h2 class="text-2xl font-bold mb-8">You Might Also Like</h2>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
    @forelse($recommendedProducts as $rec)
      <a href="{{ route('user.products.show', $rec->id) }}">
        <div class="text-center">
          <img src="{{ asset($rec->image_url) }}"
            class="w-70 h-70 max-h-70 rounded-xl mx-auto mb-3 shadow-sm object-cover hover:scale-105 transition" />

            <p class="font-medium">{{ $rec->name }}</p>
            <p class="text-gray-500 text-sm">Rp{{ number_format($rec->price, 0, ',', '.') }}</p>
        </div>
      </a>
    @empty
      <p class="col-span-4 text-center text-gray-400">No recommendations available.</p>
    @endforelse
  </div>
</section>
@endsection

@push('scripts')
<script>
  const quantity = document.getElementById("quantity");
  const increaseBtn = document.getElementById("increase");
  const decreaseBtn = document.getElementById("decrease");
  const maxStock = Number(quantity?.dataset?.max ?? 0);

  if (increaseBtn) {
    increaseBtn.addEventListener('click', () => {
      if (!quantity) return;
      const current = Number(quantity.value);
      if (maxStock > 0 && current >= maxStock) return;
      quantity.value = current + 1;
    });
  }

  if (decreaseBtn) {
    decreaseBtn.addEventListener('click', () => {
      if (!quantity) return;
      const current = Number(quantity.value);
      const minValue = maxStock > 0 ? 1 : 0;
      if (current > minValue) {
        quantity.value = current - 1;
      }
    });
  }

  // SweetAlert flash message
  document.addEventListener('DOMContentLoaded', () => {
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
      });
    @endif
  });
</script>
@endpush
