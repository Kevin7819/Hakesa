@extends('layouts.public')

@section('title', $product->name . ' - Hakesa')
@section('meta-description', Str::limit(strip_tags($product->description ?? $product->name . ' — Producto personalizado de Hakesa Costa Rica.'), 160))

@section('content')
<section class="section-padding bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center gap-2 text-sm text-gray-500">
                <li><a href="/" class="hover:text-hakesa-pink">Inicio</a></li>
                <li>/</li>
                <li><a href="{{ route('catalog.index') }}" class="hover:text-hakesa-pink">Catálogo</a></li>
                <li>/</li>
                <li class="text-gray-900 font-medium">{{ $product->name }}</li>
            </ol>
        </nav>

        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Image -->
            <div class="relative">
                @if($product->image)
                    <div class="bg-gray-100 rounded-2xl overflow-hidden flex items-center justify-center min-h-[400px]">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="bg-gradient-to-br from-hakesa-pink/20 to-hakesa-teal/20 rounded-2xl overflow-hidden flex items-center justify-center min-h-[400px]">
                        <span class="text-8xl font-extrabold text-hakesa-pink/40 select-none">H</span>
                    </div>
                @endif
                @auth
                <button x-data="wishlistToggle({{ $product->id }}, {{ $inWishlist ? 'true' : 'false' }})"
                    @click="toggle()" :disabled="loading"
                    class="absolute top-4 right-4 w-12 h-12 bg-white/95 backdrop-blur-sm rounded-full flex items-center justify-center shadow-md hover:bg-hakesa-pink/10 transition-all disabled:opacity-50 group/btn"
                    aria-label="Agregar a favoritos">
                    <svg x-show="!loading" :class="inWishlist ? 'text-hakesa-pink' : 'text-hakesa-pink group-hover/btn:text-hakesa-pink-dark'" class="w-6 h-6 drop-shadow-sm" :fill="inWishlist ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <svg x-show="loading" x-cloak class="w-5 h-5 animate-spin text-hakesa-pink" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
                @endauth
            </div>

            <!-- Details -->
            <div>
                @if($product->service_type)
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full mb-3
                        {{ $product->service_type === 'sublimacion' ? 'bg-hakesa-pink/10 text-hakesa-pink-dark' : '' }}
                        {{ $product->service_type === 'laser' ? 'bg-hakesa-teal-light/30 text-teal-700' : '' }}
                        {{ $product->service_type === 'vinil' ? 'bg-hakesa-yellow/10 text-yellow-700' : '' }}">
                        {{ ucfirst($product->service_type) }}
                    </span>
                @endif

                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                <p class="text-4xl font-extrabold text-hakesa-pink mb-6">₡{{ number_format($product->price, 0, ',', '.') }}</p>

                @if($product->description)
                    <p class="text-gray-600 mb-6 leading-relaxed">{{ $product->description }}</p>
                @endif

                <div class="flex items-center gap-4 mb-8 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ $product->stock }} disponibles
                    </span>
                </div>

                <!-- Add to Cart Form (AJAX) -->
                <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-4" x-data="addToCart('{{ route('cart.add', $product) }}')" @submit="submit($event)">
                    @csrf
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}"
                            class="w-24 px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink">
                    </div>

                    <div>
                        <label for="customization" class="block text-sm font-medium text-gray-700 mb-1">Personalización (opcional)</label>
                        <textarea name="customization" id="customization" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink"
                            placeholder="Describe cómo querés personalizar este producto (texto, diseño, colores, etc.)"></textarea>
                    </div>

                    <button type="submit" :disabled="loading" class="w-full btn-hakesa py-4 text-lg disabled:opacity-50">
                        <svg x-show="!loading" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        <svg x-show="loading" x-cloak class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-show="!loading">Agregar al Carrito</span>
                        <span x-show="loading" x-cloak>Agregando...</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Related Products -->
        @if($related->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Productos Relacionados</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($related as $item)
                <a href="{{ route('catalog.show', $item) }}" class="card-hakesa overflow-hidden group">
                    <div class="h-40 bg-gray-100 overflow-hidden">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-hakesa-pink/20 to-hakesa-teal/20">
                                <span class="text-2xl font-bold text-hakesa-pink/40">H</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 text-sm mb-1">{{ $item->name }}</h3>
                        <p class="text-hakesa-pink-dark font-bold">₡{{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
