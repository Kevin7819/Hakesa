@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-gracia-primary text-start text-base font-medium text-gracia-primary bg-gracia-primary/5 focus:outline-none focus:text-gracia-primary-dark focus:bg-gracia-primary/10 focus:border-gracia-primary-dark transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-400 hover:text-gracia-primary hover:bg-gray-900 hover:border-gracia-primary/30 focus:outline-none focus:text-gracia-primary focus:bg-gray-900 focus:border-gracia-primary/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
