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
                        class="flex-1 w-full bg-transparent border-none focus:outline-none focus:ring-0 focus:border-transparent focus-visible:outline-none focus-visible:ring-0 text-sm text-gray-700 placeholder-gray-400">
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

        <section class="mt-20">
            <h2 class="text-center text-2xl font-semibold tracking-wider mb-10">OUR PRODUCTS</h2>
            @if ($isSearching || $isFilteringCategory)
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
            @endif
        </section>
    </main>
    <a href="https://wa.link/4tyj7q" target="_blank" rel="noopener"
        class="fixed bottom-6 right-6 z-30 flex items-center justify-center w-14 h-14 rounded-full bg-[#25D366] text-white shadow-lg hover:scale-105 transition-transform"
        aria-label="Chat via WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-11 h-11" fill="currentColor" role="img"
            aria-hidden="true">
            <path
                d="M16.03 5.333c-5.92 0-10.73 4.64-10.73 10.347 0 1.93.54 3.81 1.55 5.45l-1.64 6 6.1-1.61c1.57.86 3.35 1.31 5.15 1.31 5.92 0 10.73-4.64 10.73-10.347s-4.81-10.16-10.73-10.16Zm0 18.347c-1.6 0-3.16-.42-4.53-1.21l-.32-.19-3.62.95.97-3.54-.21-.34c-.9-1.42-1.38-3.05-1.38-4.69 0-4.86 4.05-8.82 9.09-8.82s9.09 3.96 9.09 8.82-4.05 8.82-9.09 8.82Zm4.97-6.59c-.27-.13-1.59-.78-1.84-.87-.25-.09-.43-.13-.61.13-.18.25-.7.87-.86 1.05-.16.18-.32.2-.59.07-.27-.13-1.13-.41-2.15-1.3-.79-.7-1.32-1.56-1.47-1.82-.16-.27-.02-.41.11-.54.11-.11.27-.29.41-.43.14-.14.18-.23.27-.38.09-.16.05-.29-.02-.41-.07-.13-.61-1.47-.83-2.02-.23-.55-.46-.47-.61-.48h-.52c-.18 0-.48.07-.73.35-.25.27-.96.94-.96 2.29s.99 2.66 1.13 2.84c.14.18 1.94 2.95 4.71 4.13.66.29 1.18.46 1.58.59.66.21 1.27.18 1.75.11.53-.08 1.59-.65 1.82-1.27.23-.63.23-1.16.16-1.27-.07-.11-.25-.18-.52-.31Z" />
        </svg>
    </a>
@endsection
