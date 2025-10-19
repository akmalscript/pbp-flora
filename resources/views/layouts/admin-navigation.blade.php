@php
    $adminMenu = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard.view'],
        ['label' => 'Manajemen Katalog', 'route' => 'admin.catalog.manage'],
        ['label' => 'Manajemen Order', 'route' => 'admin.orders.manage'],
    ];
    $currentRouteName = request()->route()?->getName();
@endphp

<nav x-data="{ open: false }" class="bg-white text-gray-900 border-b border-orange-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-3 md:w-32"></div>

            <div class="hidden md:flex md:flex-1 md:items-center md:justify-center">
                <div class="flex items-center space-x-6">
                    @foreach ($adminMenu as $item)
                        <a href="{{ route($item['route']) }}"
                            class="px-4 py-2 text-sm font-semibold rounded-full transition
                                   {{ request()->routeIs($item['route']) ? 'bg-orange-500 text-white shadow' : 'text-gray-700 hover:text-orange-600 hover:bg-orange-100' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="hidden md:flex md:items-center md:space-x-4">
                <span class="text-sm text-gray-600">{{ Auth::user()->name ?? 'Admin' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="px-3 py-2 text-sm font-semibold rounded-full bg-orange-500 text-white hover:bg-orange-600 transition">
                        Logout
                    </button>
                </form>
            </div>

            <div class="flex items-center md:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-orange-700 hover:text-orange-900 hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-400">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden border-t border-orange-200 bg-white">
        <div class="px-4 pt-4 pb-3 space-y-2">
            @foreach ($adminMenu as $item)
                <a href="{{ route($item['route']) }}"
                    class="block px-3 py-2 rounded-md text-base font-medium transition
                           {{ request()->routeIs($item['route']) ? 'bg-orange-500 text-white shadow' : 'text-gray-700 hover:text-orange-600 hover:bg-orange-100' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <div class="border-t border-orange-200 px-4 py-3 space-y-2">
            <div class="text-sm text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</div>
            <div class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full mt-2 px-3 py-2 text-sm font-semibold rounded-full bg-orange-500 text-white hover:bg-orange-600 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>
