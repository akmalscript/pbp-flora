<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        /* Menambahkan font kustom ke Tailwind */
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #fcfcfc;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Playfair Display', serif;
        }

        .hero-text h1 {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body>

    @php
        $baseHomeRoute = request()->routeIs('user.home') ? route('user.home') : route('home');
    @endphp

    <div id="top"></div>

    <div class="mx-auto">
        <header class="relative z-20 py-4 px-6">
            <div class="flex items-center justify-between text-sm text-gray-700">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/flora_icon.png') }}" alt="Flora logo"
                        class="w-10 h-10 object-cover rounded-full">
                    <span class="text-base font-semibold tracking-[0.3em] text-gray-900">FLORA</span>
                </div>
                <nav class="flex items-center space-x-8 uppercase tracking-wider text-[#d81b60]">
                    <a href="{{ $baseHomeRoute }}#top" class="hover:text-gray-900 transition-colors">Home</a>
                    <a href="{{ $baseHomeRoute }}#catalog" class="hover:text-gray-900 transition-colors">Shop</a>
                    <button type="button" id="openContact"
                        class="hover:text-gray-900 transition-colors focus:outline-none">Contact</button>
                </nav>
                <div class="flex items-center space-x-5 text-gray-600">
                    <button type="button" id="navbarSearchTrigger" class="hover:text-gray-900 transition-colors"
                        aria-label="Search">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </button>
                    <a href="{{ route('user.cart.index') }}" class="hover:text-gray-900 transition-colors" aria-label="Cart">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                    </a>
                    <div class="relative">
                        @if (Route::has('login'))
                            <button type="button" id="profileDropdownButton"
                                class="flex items-center space-x-2 hover:text-gray-900 transition-colors focus:outline-none"
                                aria-haspopup="true" aria-expanded="false">
                                @auth
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-[#d81b60]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0" />
                                    </svg>
                                    <span class="font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0" />
                                    </svg>
                                    <span class="uppercase tracking-wider text-xs">Account</span>
                                @endauth
                            </button>

                            <div id="profileDropdownMenu"
                                class="hidden absolute right-0 mt-3 w-48 rounded-md border border-gray-100 bg-white py-2 text-xs uppercase tracking-wider shadow-lg z-30">
                                @auth
                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Profile</a>
                                    <a href="{{ route('user.orders.history') }}"
                                        class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Order History</a>
                                    <form method="POST" action="{{ route('logout') }}"
                                        class="border-t border-gray-100 mt-2 pt-2">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full px-4 py-2 text-left text-gray-600 hover:bg-gray-100">Logout</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Login</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Register</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <section class="relative bg-cover bg-center h-[500px] text-white"
            style="background-image: url('images/homepage_1.jpg')">
            <div class="absolute inset-0 bg-black bg-opacity-35"></div>
            <div class="relative h-full flex flex-col justify-center items-start max-w-xl px-12">
                <h1 class="text-5xl font-bold leading-tight drop-shadow-lg">FRESH BLOOMS & GORGEOUS GIFTS</h1>
                <p class="mt-4 text-lg text-white/90 drop-shadow">Discover Your Perfect Arrangement</p>
                <a href="#catalog"
                    class="mt-8 bg-[#D4A373] text-white py-3 px-8 rounded-sm hover:bg-opacity-90 transition-colors duration-300 text-sm font-semibold">
                    SHOP NOW
                </a>
            </div>
        </section>

        @php
            $formAction = $baseHomeRoute . '#catalog';
        @endphp

        <main id="catalog" class="py-16 px-6">
            <section class="space-y-6">
                <div class="max-w-3xl mx-auto">
                    <form action="{{ $formAction }}" method="GET"
                        class="flex flex-col sm:flex-row items-center gap-3 bg-white shadow-sm border border-gray-200 rounded-full px-6 py-3">
                        <input type="hidden" name="category" value="{{ $selectedCategory }}">
                        <input id="productSearchInput" type="text" name="search"
                            value="{{ $searchTerm ?? '' }}" placeholder="Search bouquets, gifts, or plants..."
                            class="flex-1 w-full bg-transparent focus:outline-none text-sm text-gray-700 placeholder-gray-400">
                        <button type="submit"
                            class="w-full sm:w-auto bg-[#D4A373] text-white text-sm font-semibold tracking-wide py-2 px-6 rounded-full hover:bg-opacity-90 transition-colors duration-200">
                            SEARCH
                        </button>
                    </form>
                </div>
                @php
                    $homeRoute = $baseHomeRoute;
                @endphp
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

                @php
                    $hasFeaturedFallback =
                        !$isSearching && !$isFilteringCategory && $featuredNamesRemaining->isNotEmpty();
                @endphp

                @if ($initialProducts->isEmpty() && !$hasFeaturedFallback)
                    <p class="text-center text-gray-500">
                        {{ $isSearching ? 'No products found for your search.' : ($isFilteringCategory ? 'No products available in this category yet.' : 'Products coming soon.') }}
                    </p>
                @else
                    <div class="flex flex-wrap justify-center gap-4">
                        @foreach ($initialProducts as $product)
                            <a href="{{ route('user.products.show', $product->id) }}"
                                class="group flex flex-col items-center w-52 text-center">
                                <div class="bg-gray-100 p-3 rounded-lg w-full">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                        class="w-full h-48 object-cover rounded-md group-hover:scale-105 transition-transform duration-100" />
                                </div>
                                <h4 class="mt-4 font-medium text-gray-800">{{ $product->name }}</h4>
                                <div class="flex justify-center items-center mt-2 space-x-2">
                                    <p class="text-gray-600">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                    <span class="text-gray-500 group-hover:text-black transition" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.658-.463 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                                        </svg>
                                    </span>
                                </div>
                            </a>
                        @endforeach

                        @if ($hasFeaturedFallback)
                            @php
                                $featuredCollection =
                                    $featuredProducts instanceof \Illuminate\Support\Collection
                                        ? $featuredProducts
                                        : collect($featuredProducts);
                            @endphp
                            @foreach ($featuredNamesRemaining as $featuredName)
                                @php
                                    $featured = $featuredCollection->get($featuredName);
                                    $imagePath = $featured?->image_url ?? ($featuredImages[$featuredName] ?? null);
                                    $priceValue = $featured?->price ?? 50000;
                                    $formattedPrice = 'Rp' . number_format($priceValue, 0, ',', '.');
                                    $productLink = $featured ? route('user.products.show', $featured->id) : '#';
                                    $isClickable = (bool) $featured;
                                @endphp

                                <a href="{{ $productLink }}"
                                    class="group flex flex-col items-center w-52 text-center {{ $isClickable ? '' : 'pointer-events-none opacity-60' }}">
                                    <div class="bg-gray-100 p-3 rounded-lg w-full">
                                        @if ($imagePath)
                                            <img src="{{ asset($imagePath) }}" alt="{{ $featuredName }}"
                                                class="w-full h-48 object-cover rounded-md group-hover:scale-105 transition-transform duration-100" />
                                        @endif
                                    </div>
                                    <h4 class="mt-4 font-medium text-gray-800">{{ $featuredName }}</h4>
                                    <div class="flex justify-center items-center mt-2 space-x-2">
                                        <p class="text-gray-600">{{ $formattedPrice }}</p>
                                        <span class="text-gray-500 group-hover:text-black transition"
                                            aria-hidden="true">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.658-.463 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                                            </svg>
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                @endif
            </section>
        </main>

    </div>

    <div id="contactModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50">
        <div role="dialog" aria-modal="true" aria-labelledby="contactTitle"
            class="bg-white w-full max-w-lg mx-4 rounded-2xl shadow-xl">
            <div class="p-6 border-b flex items-center justify-between">
                <h3 id="contactTitle" class="text-xl font-semibold text-slate-800">Contact Us</h3>
                <button type="button" id="closeContact"
                    class="p-2 rounded hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-rose-200"
                    aria-label="Close contact dialog">✕</button>
            </div>
            <div class="p-6 space-y-4 text-sm text-slate-600">
                <p>We're happy to help. Reach us via:</p>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <div class="mt-1 text-rose-500">🏬</div>
                        <div>
                            <p class="font-medium text-slate-800">Store Address</p>
                            <p>FSM Undip, Tembalang, Semarang</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="mt-1 text-rose-500">📞</div>
                        <div>
                            <p class="font-medium text-slate-800">Phone / WhatsApp</p>
                            <a href="tel:+6281234567890" class="text-emerald-700 hover:underline">
                                +62 812-3456-7890</a>
                            ·
                            <a href="https://wa.me/6281234567890" target="_blank"
                                class="text-emerald-700 hover:underline">Chat on WhatsApp</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="mt-1 text-rose-500">✉️</div>
                        <div>
                            <p class="font-medium text-slate-800">Email</p>
                            <a href="mailto:floralflorist@gmail.com" class="text-emerald-700 hover:underline">
                                floralflorist@gmail.com</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="mt-1 text-rose-500">📸</div>
                        <div>
                            <p class="font-medium text-slate-800">Instagram</p>
                            <a href="https://instagram.com/florafloristt" target="_blank"
                                class="text-emerald-700 hover:underline">@florafloristt</a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="p-6 border-t flex justify-end">
                <button type="button" id="okContact"
                    class="px-5 py-2 rounded-lg bg-rose-500 text-white hover:bg-rose-600 focus:outline-none focus:ring-2 focus:ring-rose-300 transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <footer id="contact" class="bg-[#333333] text-gray-300">
        <div class="max-w-7xl mx-auto py-12 px-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-8 md:space-y-0">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/flora_logo3.jpg') }}" alt="Flora logo" class="h-10 object-contain">
                </div>
                <nav class="flex space-x-8 text-sm uppercase tracking-wider">
                    <a href="#" class="hover:text-white">About Us</a>
                    <a href="#" class="hover:text-white">Contact</a>
                    <a href="#" class="hover:text-white">Shipping</a>
                </nav>
                <div class="flex space-x-6">
                    <a href="#" class="hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v2.385z" />
                        </svg>
                    </a>
                    <a href="#" class="hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616v.064c0 2.299 1.634 4.217 3.793 4.65-1.073.291-2.235.233-3.234-.04 1.684 2.409 4.404 3.492 7.03 3.53-2.053 1.611-4.648 2.529-7.34 2.164 2.138 1.393 4.67 2.208 7.398 2.208 8.868 0 13.725-7.348 13.4-13.778.948-.682 1.77-1.534 2.423-2.502z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700">
            <div class="max-w-7xl mx-auto py-3 px-6 text-center text-xs text-gray-400">
                <p>&copy; 2024. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileButton = document.getElementById('profileDropdownButton');
            const profileMenu = document.getElementById('profileDropdownMenu');
            const searchTrigger = document.getElementById('navbarSearchTrigger');
            const searchInput = document.getElementById('productSearchInput');
            const mainSection = document.querySelector('main');
            const contactModal = document.getElementById('contactModal');
            const openContactBtn = document.getElementById('openContact');
            const closeContactBtn = document.getElementById('closeContact');
            const okContactBtn = document.getElementById('okContact');

            const closeMenu = () => profileMenu.classList.add('hidden');

            if (profileButton && profileMenu) {
                profileButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    profileMenu.classList.toggle('hidden');
                    profileButton.setAttribute('aria-expanded', profileMenu.classList.contains('hidden') ?
                        'false' : 'true');
                });

                document.addEventListener('click', function(event) {
                    if (profileMenu.classList.contains('hidden')) {
                        return;
                    }

                    const clickedInsideMenu = profileMenu.contains(event.target);
                    const clickedTrigger = profileButton.contains ? profileButton.contains(event.target) :
                        false;

                    if (!clickedInsideMenu && !clickedTrigger) {
                        closeMenu();
                        profileButton.setAttribute('aria-expanded', 'false');
                    }
                });

                profileMenu.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            }

            if (searchTrigger && mainSection) {
                searchTrigger.addEventListener('click', (event) => {
                    event.preventDefault();
                    mainSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    if (searchInput) {
                        setTimeout(() => searchInput.focus(), 400);
                    }
                });
            }

            if (contactModal && openContactBtn) {
                const showContactModal = () => {
                    contactModal.classList.remove('hidden');
                    contactModal.classList.add('flex');
                    const closeButton = closeContactBtn ?? contactModal.querySelector('[data-contact-close]');
                    if (closeButton) {
                        closeButton.focus({
                            preventScroll: true
                        });
                    }
                };

                const hideContactModal = () => {
                    contactModal.classList.add('hidden');
                    contactModal.classList.remove('flex');
                    openContactBtn.focus({
                        preventScroll: true
                    });
                };

                openContactBtn.addEventListener('click', (event) => {
                    event.preventDefault();
                    showContactModal();
                });

                [closeContactBtn, okContactBtn]
                .filter((btn) => btn)
                    .forEach((btn) => btn.addEventListener('click', hideContactModal));

                contactModal.addEventListener('click', (event) => {
                    if (event.target === contactModal) {
                        hideContactModal();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && !contactModal.classList.contains('hidden')) {
                        hideContactModal();
                    }
                });
            }
        });
    </script>

</body>

</html>
