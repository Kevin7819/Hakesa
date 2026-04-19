@extends('layouts.public')

@section('title', $product->name . ' - Gracia Creativa')
@section('meta-description', Str::limit(strip_tags($product->description ?? $product->name . ' — Producto personalizado de Gracia Creativa Costa Rica.'), 160))

{{-- Schema.org JSON-LD para producto --}}
@php
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->name,
    'description' => Str::limit(strip_tags($product->description ?? ''), 160),
    'offers' => [
        '@type' => 'Offer',
        'priceCurrency' => 'CRC',
        'price' => (string) $product->price,
        'availability' => 'https://schema.org/InStock',
        'seller' => [
            '@type' => 'Organization',
            'name' => 'Gracia Creativa'
        ]
    ],
    'brand' => [
        '@type' => 'Brand',
        'name' => 'Gracia Creativa'
    ]
];
if ($product->image) {
    $jsonLd['image'] = asset('storage/' . $product->image);
}
@endphp
<script type="application/ld+json">{{ json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</script>

@section('content')
<section class="section-padding bg-gray-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center gap-2 text-sm text-gray-400">
                <li><a href="/" class="hover:text-gracia-primary">Inicio</a></li>
                <li>/</li>
                <li><a href="{{ route('catalog.index') }}" class="hover:text-gracia-primary">Catálogo</a></li>
                <li>/</li>
                <li class="text-white font-medium">{{ $product->name }}</li>
            </ol>
        </nav>

        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Image -->
            <div class="relative min-h-[400px]">
                @if($product->image)
                    <div class="bg-gray-700 rounded-2xl overflow-hidden flex items-center justify-center min-h-[400px]">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="bg-gradient-to-br from-gracia-primary/20 to-gracia-secondary/20 rounded-2xl overflow-hidden flex items-center justify-center min-h-[400px]">
                        <span class="text-8xl font-extrabold text-gracia-primary/40 select-none">GC</span>
                    </div>
                @endif
                @auth
                <button x-data="wishlistToggle({{ $product->id }}, {{ $inWishlist ? 'true' : 'false' }})"
                    @click="toggle()" :disabled="loading"
                    class="absolute top-4 right-4 w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center shadow-md hover:scale-110 hover:bg-pink-50 transition-all duration-300 disabled:opacity-50 z-10"
                    aria-label="Agregar a favoritos">
                    <svg class="w-6 h-6 transition-all duration-300" :fill="inWishlist ? '#F26BB5' : 'none'" stroke="#F26BB5" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <svg x-show="loading" x-cloak class="absolute w-5 h-5 animate-spin" fill="none" stroke="#F26BB5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
                @endauth
            </div>

            <!-- Details -->
            <div>
                @if($product->service_type)
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full mb-3
                        {{ $product->service_type === 'sublimacion' ? 'bg-gracia-primary/10 text-gracia-primary-dark' : '' }}
                        {{ $product->service_type === 'laser' ? 'bg-gracia-secondary-light/30 text-teal-700' : '' }}
                        {{ $product->service_type === 'vinil' ? 'bg-gracia-accent/10 text-yellow-700' : '' }}">
                        {{ ucfirst($product->service_type) }}
                    </span>
                @endif

                <h1 class="text-3xl font-bold text-white mb-4">{{ $product->name }}</h1>

                <p class="text-4xl font-extrabold text-gracia-primary mb-6">₡{{ number_format($product->price, 0, ',', '.') }}</p>

                @if($product->description)
                    <p class="text-gray-400 mb-6 leading-relaxed">{{ $product->description }}</p>
                @endif

                <!-- Add to Cart Form (AJAX) -->
                <form action="{{ route('cart.add', $product) }}" method="POST" class="space-y-4" x-data="addToCart('{{ route('cart.add', $product) }}')" @submit="submit($event)">
                    @csrf
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-300 mb-1">Cantidad (máx. 10)</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="10"
                            class="w-24 px-4 py-2.5 bg-gray-700 border border-gray-600 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-gracia-primary">
                    </div>

                    <div>
                        <label for="customization" class="block text-sm font-medium text-gray-300 mb-1">Personalización (opcional)</label>
                        <textarea name="customization" id="customization" rows="3"
                            class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-gracia-primary placeholder-gray-400"
                            placeholder="Describe cómo querés personalizar este producto (texto, diseño, colores, etc.)"></textarea>
                    </div>

                    <button type="submit" :disabled="loading" class="w-full btn-gracia py-4 text-lg disabled:opacity-50">
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
            <h2 class="text-2xl font-bold text-white mb-8">Productos Relacionados</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($related as $item)
                <a href="{{ route('catalog.show', $item) }}" class="card-gracia overflow-hidden group">
                    <div class="h-40 bg-gray-700 overflow-hidden">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gracia-primary/20 to-gracia-secondary/20">
                                <span class="text-2xl font-bold text-gracia-primary/40">GC</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-white text-sm mb-1">{{ $item->name }}</h3>
                        <p class="text-gracia-primary-dark font-bold">₡{{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
