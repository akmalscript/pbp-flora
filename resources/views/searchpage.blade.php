@extends('layouts.public')

@section('title', 'Pencarian Produk | Flora')

@section('content')
    <!-- Menu -->
    <!-- <nav class="flex items-center space-x-8 text-sm font-medium text-gray-600">
              <a
                href="#"
                class="hover:text-orange-500 transition"
                >POLICY</a
              >
              <a
                href="#"
                class="hover:text-orange-500 transition"
                >ABOUT</a
              >
              <a
                href="#"
                class="hover:text-orange-500 transition"
                >CONTACT</a
              >
              <a
                href="#"
                class="hover:text-orange-500 transition"
                >RETURNS</a
              >
              <button class="text-gray-400 hover:text-gray-600 transition">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-4 h-4"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                  />
                </svg>
              </button>
            </nav>
          </div>
        </header> -->

    <!-- Page Title -->
    <section class="text-center mt-14 mb-10">
        <h1 class="text-[28px] font-bold tracking-[0.6px] text-gray-900 uppercase">Search Results For</h1>
        <p class="text-[22px] text-gray-700 font-semibold mt-1">"Bouquet"</p>
    </section>

    <!-- Filters -->
    <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between mb-10 px-4 sm:px-0">
        <!-- Left -->
        <div class="flex items-center flex-wrap gap-3 text-[13px] text-gray-700 font-medium">
            <span class="font-semibold tracking-wide">Sort By:</span>
            <button class="border border-gray-300 px-3 py-1.5 rounded hover:bg-gray-50">Price Low to High</button>
            <button class="border border-gray-300 px-3 py-1.5 rounded hover:bg-gray-50">Filter</button>
            <button class="border border-gray-300 px-3 py-1.5 rounded hover:bg-gray-50">Color</button>
            <button class="border border-gray-300 px-3 py-1.5 rounded hover:bg-gray-50">Price Range</button>
        </div>

        <!-- Right -->
        <div class="flex items-center flex-wrap gap-3 mt-4 md:mt-0 text-[13px] text-gray-700 font-medium">
            <span class="font-semibold tracking-wide">Filter By:</span>
            <button class="border border-gray-300 px-3 py-1.5 rounded hover:bg-gray-50">Flowers</button>
            <button class="border border-gray-300 px-3 py-1.5 rounded hover:bg-gray-50">Price Range</button>
        </div>
    </div>

    <!-- Product Grid -->
    <section class="max-w-6xl mx-auto grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-8 mb-16 px-4 sm:px-0">
        <!-- Product Card -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-md p-4 text-center hover:shadow-md transition">
            <img src="{{ asset('images/bouquet/lily.jpg') }}" alt="Lily Bouquet"
                class="w-full h-48 object-cover rounded-md mb-3" />
            <p class="font-medium text-gray-800 text-[15px] tracking-tight">Lily Bouquet</p>
            <p class="text-[13px] text-gray-500 mt-0.5">$45.00</p>
        </div>

        <div class="bg-white border border-gray-200 shadow-sm rounded-md p-4 text-center hover:shadow-md transition">
            <img src="{{ asset('images/bouquet/mix.jpg') }}" alt="Mix Bouquet"
                class="w-full h-48 object-cover rounded-md mb-3" />
            <p class="font-medium text-gray-800 text-[15px] tracking-tight">Mix Bouquet</p>
            <p class="text-[13px] text-gray-500 mt-0.5">$38.50</p>
        </div>

        <div class="bg-white border border-gray-200 shadow-sm rounded-md p-4 text-center hover:shadow-md transition">
            <img src="{{ asset('images/bouquet/rose.jpg') }}" alt="Rose Bouquet"
                class="w-full h-48 object-cover rounded-md mb-3" />
            <p class="font-medium text-gray-800 text-[15px] tracking-tight">Rose Bouquet</p>
            <p class="text-[13px] text-gray-500 mt-0.5">$42.00</p>
        </div>

        <div class="bg-white border border-gray-200 shadow-sm rounded-md p-4 text-center hover:shadow-md transition">
            <img src="{{ asset('images/bouquet/sunflower.jpg') }}" alt="Sunflower Bouquet"
                class="w-full h-48 object-cover rounded-md mb-3" />
            <p class="font-medium text-gray-800 text-[15px] tracking-tight">Sunflower Bouquet</p>
            <p class="text-[13px] text-gray-500 mt-0.5">$40.00</p>
        </div>

        <div class="bg-white border border-gray-200 shadow-sm rounded-md p-4 text-center hover:shadow-md transition">
            <img src="{{ asset('images/bouquet/peony.jpg') }}" alt="Peony Bouquet"
                class="w-full h-48 object-cover rounded-md mb-3" />
            <p class="font-medium text-gray-800 text-[15px] tracking-tight">Peony Bouquet</p>
            <p class="text-[13px] text-gray-500 mt-0.5">$55.00</p>
        </div>

        <div class="bg-white border border-gray-200 shadow-sm rounded-md p-4 text-center hover:shadow-md transition">
            <img src="{{ asset('images/bouquet/orchid.jpg') }}" alt="Orchid Bouquet"
                class="w-full h-48 object-cover rounded-md mb-3" />
            <p class="font-medium text-gray-800 text-[15px] tracking-tight">Orchid Bouquet</p>
            <p class="text-[13px] text-gray-500 mt-0.5">$62.00</p>
        </div>
    </section>

    <!-- Pagination -->
    <div class="flex justify-center items-center space-x-2 mb-20 text-[13px]">
        <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">1</button>
        <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">2</button>
        <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">3</button>
        <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">Next</button>
    </div>
@endsection
