@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-hakesa-pink text-start text-base font-medium text-hakesa-pink bg-hakesa-pink/5 focus:outline-none focus:text-hakesa-pink-dark focus:bg-hakesa-pink/10 focus:border-hakesa-pink-dark transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-hakesa-pink hover:bg-hakesa-light hover:border-hakesa-pink/30 focus:outline-none focus:text-hakesa-pink focus:bg-hakesa-light focus:border-hakesa-pink/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
