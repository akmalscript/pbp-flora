@extends('layouts.app')

@section('content')
        <!-- Menu -->
        <nav class="flex items-center space-x-8 text-sm font-medium text-gray-600">
          <a href="#" class="hover:text-orange-500 transition">POLICY</a>
          <a href="#" class="hover:text-orange-500 transition">ABOUT</a>
          <a href="#" class="hover:text-orange-500 transition">CONTACT</a>
          <a href="#" class="hover:text-orange-500 transition">RETURNS</a>
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
    </header>

    <!-- Page Title -->
    <section class="text-center mt-12 mb-8">
      <h1 class="text-3xl font-bold tracking-wide text-gray-900">ORDER MANAGEMENT</h1>
    </section>

    <!-- Main Grid -->
    <main class="max-w-6xl mx-auto grid md:grid-cols-1 gap-8 mb-16">
      <!-- Product Management -->
      <section class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <h2 class="text-lg font-semibold mb-4">PRODUCT MANAGEMENT</h2>

        <div class="flex items-center justify-between mb-4">
          <div class="relative w-1/2">
            <input
              type="text"
              placeholder="Search"
              class="w-full border border-gray-300 rounded-md pl-8 pr-3 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none"
            />
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-4 h-4 absolute left-2 top-2.5 text-gray-400"
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
          </div>
          <button class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium">ADD NEW PRODUCT</button>
        </div>

        <table class="w-full text-sm border-t">
          <thead class="text-left text-gray-600 border-b">
            <tr>
              <th class="py-2">Image</th>
              <th>Date</th>
              <th>Stock</th>
              <th></th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <tr>
              <td class="py-3 flex items-center gap-2">
                <img
                  src="images/lily.jpg"
                  class="w-12 h-12 rounded-md object-cover border"
                  alt="Lily Bouquet"
                />
                <span>Lily Bouquet</span>
              </td>
              <td>Processing</td>
              <td>20K</td>
              <td><button class="text-orange-500 text-xs font-semibold hover:underline">Edit</button></td>
            </tr>
            <tr>
              <td class="py-3 flex items-center gap-2">
                <img
                  src="images/mix.jpg"
                  class="w-12 h-12 rounded-md object-cover border"
                  alt="Mix Bouquet"
                />
                <span>Mix Bouquet</span>
              </td>
              <td>Shipped</td>
              <td>45K</td>
              <td><button class="text-orange-500 text-xs font-semibold hover:underline">Edit</button></td>
            </tr>
            <tr>
              <td class="py-3 flex items-center gap-2">
                <img
                  src="images/rose.jpg"
                  class="w-12 h-12 rounded-md object-cover border"
                  alt="Seasonal Delivery"
                />
                <span>Rose Bouquet</span>
              </td>
              <td>Delivery</td>
              <td>55K</td>
              <td><button class="text-orange-500 text-xs font-semibold hover:underline">Edit</button></td>
            </tr>
          </tbody>
        </table>
      </section>
    </main>

@endsection