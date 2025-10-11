@extends('layouts.app')

@section('content')
  <!-- MAIN CONTENT -->
  <main class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-2 gap-12 items-start">
    
    <!-- LEFT: IMAGE GALLERY -->
    <div class="space-y-4">
      <img id="mainImage" src="vaseFlower2.jpg" alt="Product" class="w-full rounded-2xl shadow-lg object-cover" />
      <div class="flex gap-3">
        <img src="VaseFlower1.jpg" class="w-24 h-24 object-cover rounded-lg cursor-pointer border hover:border-orange-500 transition" onclick="changeImage(this.src)" />
        <img src="vaseFlower3.jpg" class="w-24 h-24 object-cover rounded-lg cursor-pointer border hover:border-orange-500 transition" onclick="changeImage(this.src)" />
        <img src="vaseFlower4.jpg" class="w-24 h-24 object-cover rounded-lg cursor-pointer border hover:border-orange-500 transition" onclick="changeImage(this.src)" />
      </div>
    </div>

    <!-- RIGHT: PRODUCT INFO -->
    <div>
      <h1 class="text-4xl font-bold mb-2">Mini Vase Flower</h1>

      <!-- RATING -->
      <div class="flex items-center gap-2 mb-4">
        <div class="flex text-yellow-400 text-lg">
          ★★★★★
        </div>
        <p class="text-sm text-gray-500">(170 reviews)</p>
      </div>

      <p class="text-gray-500 mb-6 leading-relaxed">
        Bring instant good vibes to your space with our Mini Vase Flower Collection. 
        Each piece is a tiny burst of color and calm — a mix of dreamy pastel blooms styled in cute little vases that make any corner feel alive. 
        It’s the kind of decor that doesn’t try too hard but still steals the spotlight. 
        Perfect for your desk, your shelf, or as a small gift that says “I care” without needing too many words. Every arrangement is unique, just like your mood — soft, fun, and effortlessly pretty.
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
      <div class="text-center">
        <img src="BloomBag.jpg" class="rounded-xl mx-auto mb-3 shadow-sm hover:scale-105 transition" />
        <p class="font-medium">Bloom Bag Flower</p>
        <p class="text-gray-500 text-sm">Rp100.000</p>
      </div>
      <div class="text-center">
        <img src="Flowerbouqet.jpg" class="rounded-xl mx-auto mb-3 shadow-sm hover:scale-105 transition" />
        <p class="font-medium">Flower Bouquet</p>
        <p class="text-gray-500 text-sm">Rp250.000</p>
      </div>
      <div class="text-center">
        <img src="flowerstand.jpg" class="rounded-xl mx-auto mb-3 shadow-sm hover:scale-105 transition" />
        <p class="font-medium">Flower Stand</p>
        <p class="text-gray-500 text-sm">Rp150.000</p>
      </div>
      <div class="text-center">
        <img src="dried flower akrilik.jpg" class="rounded-xl mx-auto mb-3 shadow-sm hover:scale-105 transition" />
        <p class="font-medium">Acrylic Dried Flower Frame</p>
        <p class="text-gray-500 text-sm">Rp100.000</p>
      </div>
    </div>
  </section>

  @endsection

  @push('scripts')
  <!-- SCRIPT -->
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

    document.getElementById("cartBtn").onclick = () => {
      alert("Opening your cart (dummy action)");
    };
  </script>
@endpush
