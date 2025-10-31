@extends('layouts.public')

@section('title', 'Flora')

@section('show_home_search', true)

@section('content')
    @php
        $baseHomeRoute = request()->routeIs('user.home') ? route('user.home') : route('home');
        $formAction = $baseHomeRoute . '#catalog';
        $homeRoute = $baseHomeRoute;
    @endphp

    <section class="relative bg-cover bg-center h-[500px] text-white"
        style="background-image: url('{{ asset('images/homepage_1.jpg') }}')">
        <div class="absolute inset-0 bg-black bg-opacity-35"></div>
        <div class="relative h-full flex flex-col justify-center items-start max-w-xl px-12">
            <h1 class="text-5xl font-bold leading-tight drop-shadow-lg">FRESH BLOOMS &amp; GORGEOUS GIFTS</h1>
            <p class="mt-4 text-lg text-white/90 drop-shadow">Discover Your Perfect Arrangement</p>
            <a href="#catalog"
                class="mt-8 bg-[#D4A373] text-white py-3 px-8 rounded-sm hover:bg-opacity-90 transition-colors duration-300 text-sm font-semibold">
                SHOP NOW
            </a>
        </div>
    </section>

    <main id="catalog" class="py-16 px-6">
        <section class="space-y-6">
            <div class="max-w-3xl mx-auto">
                <form action="{{ $formAction }}" method="GET"
                    class="flex flex-col sm:flex-row items-center gap-3 bg-white shadow-sm border border-gray-200 rounded-full px-6 py-3 focus-within:border-[#D4A373] focus-within:shadow-md focus-within:outline-none">
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                    <input id="productSearchInput" type="text" name="search" value="{{ $searchTerm ?? '' }}"
                        placeholder="Search bouquets, gifts, or plants..."
                        class="flex-1 w-full bg-transparent border-none focus:outline-none focus:ring-0 focus:border-transparent focus-visible:outline-none focus-visible:ring-0 text-sm text-gray-700 placeholder-gray-400"
                        autocomplete="off" />
                    <button type="submit"
                        class="w-full sm:w-auto bg-[#D4A373] text-white text-sm font-semibold tracking-wide py-2 px-6 rounded-full hover:bg-opacity-90 transition-colors duration-200">
                        SEARCH
                    </button>
                </form>
            </div>
            <div class="flex flex-wrap justify-center gap-4 text-sm sm:text-base tracking-widest uppercase">
                @foreach ($categories as $slug => $label)
                    @php
                        $queryParams = array_filter(
                            [
                                'search' => $searchTerm,
                                'category' => $slug,
                            ],
                            fn($value) => $value !== null && $value !== '',
                        );
                        $categoryUrl =
                            $homeRoute . ($queryParams ? '?' . http_build_query($queryParams) : '') . '#catalog';
                        $isActiveCategory = $selectedCategory === $slug;
                    @endphp
                    <a href="{{ $categoryUrl }}"
                        class="px-5 py-3 rounded-full border transition-colors duration-200 {{ $isActiveCategory ? 'bg-[#D4A373] text-white border-[#D4A373]' : 'border-gray-300 text-gray-700 hover:border-[#D4A373] hover:text-[#D4A373]' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </section>

        <section class="mt-10">
            <h2 class="text-center text-2xl font-semibold tracking-wider mb-10">OUR PRODUCTS</h2>
            @if (($isSearching || $isFilteringCategory) && $displayProducts->isEmpty() === false)
                <p class="text-center text-sm text-gray-600 mb-6">
                    Showing {{ $totalResults }} {{ $totalResults === 1 ? 'product' : 'products' }}
                    @if ($isSearching)
                        for "{{ $searchTerm }}"
                    @endif
                    @if ($isFilteringCategory)
                        {{ $isSearching ? 'in' : 'for' }} {{ $selectedCategoryLabel }}
                    @endif
                </p>
            @endif

            @if ($displayProducts->isEmpty())
                <p class="text-center text-gray-500">
                    {{ $isSearching ? 'No products found for your search.' : ($isFilteringCategory ? 'No products available in this category yet.' : 'Products coming soon.') }}
                </p>
            @else
                <div class="flex flex-wrap justify-center gap-4">
                    @foreach ($displayProducts as $card)
                        @php
                            $wrapperClasses = $card['is_clickable']
                                ? ''
                                : ($card['is_placeholder']
                                    ? 'pointer-events-none opacity-60'
                                    : 'pointer-events-none');
                            $imageHoverClass =
                                $card['is_clickable'] && !$card['is_out_of_stock'] ? 'group-hover:scale-105' : '';
                            $iconClass = $card['is_out_of_stock']
                                ? 'opacity-50'
                                : ($card['is_clickable']
                                    ? 'group-hover:text-black transition'
                                    : '');
                            $priceLabel = 'Rp' . number_format($card['price'], 0, ',', '.');
                        @endphp

                        <a href="{{ $card['link'] }}"
                            class="group flex flex-col items-center w-52 text-center {{ $wrapperClasses }}">
                            <div
                                class="relative bg-gray-100 p-3 rounded-lg w-full overflow-hidden {{ $card['is_out_of_stock'] ? 'opacity-60' : '' }}">
                                @if (!empty($card['image_url']))
                                    <img src="{{ $card['image_url'] }}" alt="{{ $card['name'] }}"
                                        class="w-full h-48 object-cover rounded-md transition-transform duration-100 {{ $imageHoverClass }}" />
                                @endif
                                @if ($card['is_out_of_stock'])
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold tracking-wider uppercase">Stok
                                            Habis</span>
                                    </div>
                                @endif
                            </div>
                            <h4 class="mt-4 font-medium text-gray-800">{{ $card['name'] }}</h4>
                            <div class="flex justify-center items-center mt-2 space-x-2">
                                <p class="text-gray-600">{{ $priceLabel }}</p>
                                <span class="text-gray-500 {{ $iconClass }}" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.658-.463 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                                    </svg>
                                </span>
                            </div>
                            @if ($card['show_stock'])
                                <p class="text-xs mt-1 {{ $card['is_out_of_stock'] ? 'text-red-500' : 'text-green-600' }}">
                                    {{ $card['is_out_of_stock'] ? 'Stok habis' : 'Stok: ' . $card['stock'] }}
                                </p>
                            @endif
                        </a>
                    @endforeach
                </div>
                <div class="mt-12 -mb-6">
                    {{ $productsPaginator->fragment('catalog')->links() }}
                </div>
            @endif
        </section>
    </main>
@endsection
