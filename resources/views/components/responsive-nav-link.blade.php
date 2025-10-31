@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-orange-400 dark:border-orange-400 text-start text-base font-medium text-orange-600 dark:text-orange-300 bg-orange-50/80 dark:bg-orange-900/40 focus:outline-none focus:text-orange-700 dark:focus:text-orange-200 focus:bg-orange-100 dark:focus:bg-orange-900/50 focus:border-orange-500 dark:focus:border-orange-300 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-orange-50/60 dark:hover:bg-orange-900/30 hover:border-orange-300 dark:hover:border-orange-400 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-orange-50/60 dark:focus:bg-orange-900/30 focus:border-orange-300 dark:focus:border-orange-400 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
