@extends('layouts.app')

@section('content')
<!-- MAIN CONTENT -->
<main class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-12 items-start">

  <!-- LEFT: IMAGE GALLERY -->
  <div class="space-y-4">
      <img 
          id="mainImage" 
          src="{{ asset($product->image_url) }}" 
          alt="{{ $product->name }}" 
          class="w-full rounded-2xl shadow-lg object-cover" 
      />

      <div class="flex gap-3">
          @foreach($product->photos as $photo)
              <img 
                  src="{{ asset($photo->url) }}" 
                  class="thumbnail w-24 h-24 object-cover rounded-lg cursor-pointer border hover:border-orange-500 transition" 
                  onclick="changeImage(this)" 
              />
          @endforeach
      </div>
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

    <!-- QUANTITY + ADD TO CART -->
    <form action="{{ route('user.cart.store') }}" method="POST">
      @csrf
      <input type="hidden" name="product_id" value="{{ $product->id }}">
      <div class="flex items-center gap-4 mb-8">
        <div class="flex items-center border rounded-lg">
          <button type="button" id="decrease" class="px-4 py-2 text-xl hover:bg-gray-100">-</button>
          <input id="quantity" name="quantity" type="text" value="1" readonly class="w-12 text-center border-x outline-none text-lg" />
          <button type="button" id="increase" class="px-4 py-2 text-xl hover:bg-gray-100">+</button>
        </div>
        <button type="submit" class="bg-orange-500 text-white px-8 py-3 rounded-lg text-sm font-medium hover:bg-orange-600 transition">
          Add to Cart
        </button>
      </div>
    </form>

  </div>
</main>

<!-- RECOMMENDATION SECTION -->
<section class="max-w-7xl mx-auto px-6 mt-20">
  <h2 class="text-2xl font-bold mb-8">You Might Also Like</h2>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
    @forelse($recommendedProducts as $rec)
      <a href="{{ route('user.products.show', $rec->id) }}">
        <div class="text-center">
            <img src="{{ asset($rec->image_url) }}" 
                 class="rounded-xl mx-auto mb-3 shadow-sm hover:scale-105 transition" />

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
  const mainImage = document.getElementById("mainImage");
  const thumbnails = document.querySelectorAll(".thumbnail");
  const quantity = document.getElementById("quantity");
  const increaseBtn = document.getElementById("increase");
  const decreaseBtn = document.getElementById("decrease");

  // Change main image and highlight selected thumbnail
  function changeImage(img) {
    mainImage.src = img.src;
    thumbnails.forEach(t => t.classList.remove('border-orange-500', 'border-2'));
    img.classList.add('border-orange-500', 'border-2');
  }

  // Quantity buttons
  increaseBtn.onclick = () => quantity.value = Number(quantity.value) + 1;
  decreaseBtn.onclick = () => {
    if (Number(quantity.value) > 1) quantity.value = Number(quantity.value) - 1;
  };

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
