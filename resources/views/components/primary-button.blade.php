<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gracia-primary border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-gracia-primary-dark focus:bg-gracia-primary-dark active:bg-gracia-primary-dark focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-gracia-primary/25']) }}>
    {{ $slot }}
</button>
