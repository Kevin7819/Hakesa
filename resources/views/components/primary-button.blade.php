<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-hakesa-pink border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-hakesa-pink-dark focus:bg-hakesa-pink-dark active:bg-hakesa-pink-dark focus:outline-none focus:ring-2 focus:ring-hakesa-pink focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-hakesa-pink/25']) }}>
    {{ $slot }}
</button>
