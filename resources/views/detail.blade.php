@extends('layouts.app')

@section('content')
  <!-- MAIN CONTENT -->
  <main class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-12 items-start">
    
    <!-- LEFT: IMAGE GALLERY -->
    <div class="space-y-4">
      <img id="mainImage" src="{{ asset('images/tulip.jpg') }}" alt="Product" class="w-full rounded-2xl shadow-lg object-cover" />
      <div class="flex gap-3">
        <img src="{{ asset('images/tulip.jpg') }}" class="w-24 h-24 object-cover rounded-lg cursor-pointer border hover:border-orange-500 transition" onclick="changeImage(this.src)" />
        <img src="{{ asset('images/sunflower.jpg') }}" class="w-24 h-24 object-cover rounded-lg cursor-pointer border hover:border-orange-500 transition" onclick="changeImage(this.src)" />
        <img src="{{ asset('images/Bunga 3.jpeg') }}" class="w-24 h-24 object-cover rounded-lg cursor-pointer border hover:border-orange-500 transition" onclick="changeImage(this.src)" />
      </div>
    </div>

    <!-- RIGHT: PRODUCT INFO -->
    <div>
      <h1 class="text-4xl font-bold mb-2">Mini Vase Flower</h1>

      <p class="text-gray-500 mb-6 leading-relaxed">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit corrupti natus deserunt sunt mollitia nemo quidem ipsam, praesentium fugiat voluptates minus ab illo voluptatum nesciunt sequi perferendis aliquid veniam accusamus.
      </p>

      <!-- PRICE WITH DISCOUNT -->
      <div class="flex items-baseline gap-3 mb-6">
        <p class="text-3xl font-semibold text-orange-600">Rp200.000</p>
        <p class="text-gray-400 line-through text-lg">Rp250.000</p>
        <span class="bg-red-100 text-red-600 text-sm font-semibold px-2 py-1 rounded">-20%</span>
      </div>

      <!-- QUANTITY + ADD TO CART -->
      <div class="flex items-center gap-4 mb-8">
        <div class="flex items-center border rounded-lg">
          <button id="decrease" class="px-4 py-2 text-xl hover:bg-gray-100">−</button>
          <input id="quantity" type="text" value="1" readonly class="w-12 text-center border-x outline-none text-lg" />
          <button id="increase" class="px-4 py-2 text-xl hover:bg-gray-100">+</button>
        </div>
        <button id="addToCart" class="bg-orange-500 text-white px-8 py-3 rounded-lg text-sm font-medium hover:bg-orange-600 transition">
          Add to Cart
        </button>
      </div>
    </div>
  </main>

  <!-- RECOMMENDATION SECTION -->
  <section class="max-w-7xl mx-auto px-6 mt-20">
    <h2 class="text-2xl font-bold mb-8">You Might Also Like</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
      <!-- <div class="text-center">
        <img src="BloomBag.jpg" class="rounded-xl mx-auto mb-3 shadow-sm hover:scale-105 transition" />
        <p class="font-medium">Bloom Bag Flower</p>
        <p class="text-gray-500 text-sm">Rp100.000</p>
      </div> -->
      <!-- recommendation logic here -->
    </div>
  </section>
@endsection

@push('scripts')
<script>
  function changeImage(src) {
    document.getElementById("mainImage").src = src;
  }

  const quantity = document.getElementById("quantity");
  document.getElementById("increase").onclick = () => quantity.value = parseInt(quantity.value) + 1;
  document.getElementById("decrease").onclick = () => {
    if (parseInt(quantity.value) > 1) quantity.value = parseInt(quantity.value) - 1;
  };

  document.getElementById("addToCart").onclick = () => {
    alert(`Added ${quantity.value} item(s) to your cart!`);
  };

  // document.getElementById("cartBtn").onclick = () => {
  //   alert("Opening your cart (dummy action)");
  // };
</script>
@endpush

