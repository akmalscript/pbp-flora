<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Flora')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>

<body>
    @php
        $baseHomeRoute = request()->routeIs('user.home') ? route('user.home') : route('home');
    @endphp

    <div id="top"></div>

    <div class="mx-auto min-h-screen flex flex-col">
        <header class="relative z-20 py-4 px-6">
            <div class="flex items-center justify-between text-sm text-gray-700">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/flora_icon.png') }}" alt="Flora logo"
                        class="w-10 h-10 object-cover rounded-full">
                    <span class="text-base font-semibold tracking-[0.3em] text-gray-900">FLORA</span>
                </div>
                <div class="flex items-center space-x-5 text-gray-600">
                    <nav class="flex items-center space-x-8 uppercase tracking-wider text-gray-900">
                        <a href="{{ $baseHomeRoute }}#top"
                            class="relative py-1 text-gray-700 transition-colors duration-200 hover:text-[#D4A373] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-full after:origin-left after:scale-x-0 after:bg-[#D4A373] after:transition-transform after:duration-200 after:content-[''] hover:after:scale-x-100">Home</a>
                        <a href="{{ $baseHomeRoute }}#catalog"
                            class="relative py-1 text-gray-700 transition-colors duration-200 hover:text-[#D4A373] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-full after:origin-left after:scale-x-0 after:bg-[#D4A373] after:transition-transform after:duration-200 after:content-[''] hover:after:scale-x-100">Shop</a>
                        <button type="button" id="openContact"
                            class="relative py-1 text-gray-700 transition-colors duration-200 hover:text-[#D4A373] focus:outline-none after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-full after:origin-left after:scale-x-0 after:bg-[#D4A373] after:transition-transform after:duration-200 after:content-[''] hover:after:scale-x-100">CONTACT</button>
                    </nav>
                    @hasSection('show_home_search')
                        <button type="button" id="navbarSearchTrigger"
                            class="relative flex items-center justify-center text-gray-600 transition-colors duration-200 hover:text-[#D4A373] focus:outline-none after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-full after:origin-left after:scale-x-0 after:bg-[#D4A373] after:transition-transform after:duration-200 after:content-[''] hover:after:scale-x-100"
                            aria-label="Search">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </button>
                    @endif
                    <a href="{{ route('user.cart.index') }}"
                        class="relative flex items-center justify-center text-gray-600 transition-colors duration-200 hover:text-[#D4A373] after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-full after:origin-left after:scale-x-0 after:bg-[#D4A373] after:transition-transform after:duration-200 after:content-[''] hover:after:scale-x-100"
                        aria-label="Cart">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                    </a>
                    <div class="relative">
                        @if (Route::has('login'))
                            <button type="button" id="profileDropdownButton"
                                class="group relative flex items-center space-x-2 text-gray-600 transition-colors duration-200 hover:text-[#D4A373] focus:outline-none after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-full after:origin-left after:scale-x-0 after:bg-[#D4A373] after:transition-transform after:duration-200 after:content-[''] hover:after:scale-x-100"
                                aria-haspopup="true" aria-expanded="false">
                                @auth
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5 text-[#d81b60] transition-colors duration-200 group-hover:text-[#D4A373]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0" />
                                    </svg>
                                    <span
                                        class="font-semibold text-gray-700 transition-colors duration-200 group-hover:text-[#D4A373]">{{ Auth::user()->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5 transition-colors duration-200 group-hover:text-[#D4A373]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a8.25 8.25 0 0 1 15 0" />
                                    </svg>
                                    <span
                                        class="uppercase tracking-wider text-xs transition-colors duration-200 group-hover:text-[#D4A373]">Account</span>
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

        <div class="flex-1">
            @yield('content')
            <a href="https://wa.link/4tyj7q" target="_blank" rel="noopener"
                class="fixed bottom-6 right-6 z-30 flex items-center justify-center w-14 h-14 rounded-full bg-[#25D366] text-white shadow-lg hover:scale-105 transition-transform"
                aria-label="Chat via WhatsApp">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-11 h-11" fill="currentColor" role="img"
                    aria-hidden="true">
                    <path
                        d="M16.03 5.333c-5.92 0-10.73 4.64-10.73 10.347 0 1.93.54 3.81 1.55 5.45l-1.64 6 6.1-1.61c1.57.86 3.35 1.31 5.15 1.31 5.92 0 10.73-4.64 10.73-10.347s-4.81-10.16-10.73-10.16Zm0 18.347c-1.6 0-3.16-.42-4.53-1.21l-.32-.19-3.62.95.97-3.54-.21-.34c-.9-1.42-1.38-3.05-1.38-4.69 0-4.86 4.05-8.82 9.09-8.82s9.09 3.96 9.09 8.82-4.05 8.82-9.09 8.82Zm4.97-6.59c-.27-.13-1.59-.78-1.84-.87-.25-.09-.43-.13-.61.13-.18.25-.7.87-.86 1.05-.16.18-.32.2-.59.07-.27-.13-1.13-.41-2.15-1.3-.79-.7-1.32-1.56-1.47-1.82-.16-.27-.02-.41.11-.54.11-.11.27-.29.41-.43.14-.14.18-.23.27-.38.09-.16.05-.29-.02-.41-.07-.13-.61-1.47-.83-2.02-.23-.55-.46-.47-.61-.48h-.52c-.18 0-.48.07-.73.35-.25.27-.96.94-.96 2.29s.99 2.66 1.13 2.84c.14.18 1.94 2.95 4.71 4.13.66.29 1.18.46 1.58.59.66.21 1.27.18 1.75.11.53-.08 1.59-.65 1.82-1.27.23-.63.23-1.16.16-1.27-.07-.11-.25-.18-.52-.31Z" />
                </svg>
            </a>
        </div>
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
                            <a href="tel:+62878-2533-4959" class="text-emerald-700 hover:underline">
                                +62 878-2533-4959</a>
                            ·
                            <a href="https://wa.me/6287825334959" target="_blank"
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

    <footer id="contact" class="bg-[#333333] text-gray-300 mt-auto">
        <div class="border-t border-gray-700">
            <div class="max-w-7xl mx-auto py-10 px-6 text-center text-sm text-gray-200">
                <p>Copyright &copy; 2025 FLORA. All rights reserved.</p>
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

            const closeMenu = () => profileMenu?.classList.add('hidden');

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
                    openContactBtn?.focus({
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
    @stack('scripts')
</body>

</html>
