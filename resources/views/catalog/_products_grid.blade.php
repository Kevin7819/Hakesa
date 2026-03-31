@if($products->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($products as $product)
    <div class="card-hakesa overflow-hidden group">
        <a href="{{ route('catalog.show', $product) }}">
            <div class="h-52 bg-gray-100 overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-hakesa-pink/10 to-hakesa-teal/10">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>
        </a>
        <div class="p-5">
            @if($product->category)
                <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full mb-2 bg-hakesa-teal/10 text-teal-700">
                    {{ $product->category->name }}
                </span>
            @endif
            <a href="{{ route('catalog.show', $product) }}">
                <h3 class="font-bold text-gray-900 mb-1 hover:text-hakesa-pink transition-colors">{{ $product->name }}</h3>
            </a>
            <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
            <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-hakesa-pink">₡{{ number_format($product->price, 0, ',', '.') }}</span>
                <form action="{{ route('cart.add', $product) }}" method="POST" x-data="addToCart('{{ route('cart.add', $product) }}')" @submit="submit($event)">
                    @csrf
                    <button type="submit" :disabled="loading" :aria-label="'Agregar ' + @js($product->name) + ' al carrito'" class="w-10 h-10 rounded-xl bg-hakesa-pink/10 text-hakesa-pink hover:bg-hakesa-pink hover:text-white flex items-center justify-center transition-colors disabled:opacity-50">
                        <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        <svg x-show="loading" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-20">
    <div class="w-24 h-24 bg-hakesa-pink/10 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12 text-hakesa-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    </div>
    <h3 class="text-2xl font-bold text-gray-900 mb-2">No se encontraron productos</h3>
    <p class="text-gray-500 mb-4">Probá con otros filtros de búsqueda</p>
</div>
@endif
