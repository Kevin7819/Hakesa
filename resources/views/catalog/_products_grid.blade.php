@if($products->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @php
        $wlIds = $wishlistIds ?? [];
    @endphp
    @foreach($products as $product)
    <div class="card-hakesa overflow-hidden group">
        <div class="relative h-52">
            <a href="{{ route('catalog.show', $product) }}" class="block h-full">
                @if($product->image)
                    <div class="h-full bg-gray-700 overflow-hidden">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                @else
                    <div class="h-full overflow-hidden bg-gradient-to-br from-gracia-primary/20 to-gracia-secondary/20 flex items-center justify-center">
                        <span class="text-5xl font-extrabold text-gracia-primary/40 select-none">H</span>
                    </div>
                @endif
            </a>
            @auth
            <button x-data="wishlistToggle({{ $product->id }}, {{ in_array($product->id, $wlIds) ? 'true' : 'false' }})"
                @click="toggle()" :disabled="loading"
                class="absolute top-3 right-3 w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center shadow-md hover:scale-110 hover:bg-gracia-primary/20 transition-all duration-300 disabled:opacity-50 z-10"
                aria-label="Agregar a favoritos">
                <svg class="w-5 h-5 transition-all duration-300" :fill="inWishlist ? '#BF5098' : 'none'" stroke="#BF5098" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <svg x-show="loading" x-cloak class="absolute w-4 h-4 animate-spin" fill="none" stroke="#BF5098" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
            @endauth
        </div>
        <div class="p-5">
            @if($product->category)
                <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full mb-2 bg-gracia-secondary/20 text-gracia-secondary-light">
                    {{ $product->category->name }}
                </span>
            @endif
            <a href="{{ route('catalog.show', $product) }}">
                <h3 class="font-bold text-white mb-1 hover:text-gracia-primary transition-colors">{{ $product->name }}</h3>
            </a>
            <p class="text-gray-400 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
            <div class="flex justify-between items-center">
                <span class="text-xl font-bold text-gracia-primary-dark">₡{{ number_format($product->price, 0, ',', '.') }}</span>
                <form action="{{ route('cart.add', $product) }}" method="POST" x-data="addToCart('{{ route('cart.add', $product) }}')" @submit="submit($event)">
                    @csrf
                    <button type="submit" :disabled="loading" :aria-label="'Agregar ' + @js($product->name) + ' al carrito'" class="w-10 h-10 rounded-xl bg-gracia-primary/10 text-gracia-primary hover:bg-gracia-primary hover:text-white flex items-center justify-center transition-colors disabled:opacity-50">
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
    <div class="w-24 h-24 bg-gracia-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-12 h-12 text-gracia-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    </div>
    <h3 class="text-2xl font-bold text-white mb-2">No se encontraron productos</h3>
    <p class="text-gray-400 mb-4">Probá con otros filtros de búsqueda</p>
</div>
@endif
