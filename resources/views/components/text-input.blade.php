@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-hakesa-pink focus:ring-hakesa-pink rounded-xl shadow-sm']) }}>
