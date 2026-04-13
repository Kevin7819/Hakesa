@extends('layouts.public')

@section('title', 'Mis Favoritos - Gracia Creativa')
@section('meta-description', 'Productos que has guardado como favoritos en Gracia Creativa Costa Rica.')

@section('content')
<section class="section-padding bg-gray-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center gap-2 text-sm text-gray-400">
                <li><a href="/" class="hover:text-gracia-primary">Inicio</a></li>
                <li>/</li>
                <li class="text-white font-medium">Mis Favoritos</li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold text-white mb-2">Mis Favoritos</h1>
        <p class="text-gray-400 mb-8">Productos que has guardado para después</p>

        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6">{{ session('success') }}</div>
        @endif

        @if($wishlistItems->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($wishlistItems as $item)
            @if($item->product && $item->product->is_active)
            <div class="card-hakesa overflow-hidden group" x-data="wishlistItem({{ $item->product->id }})">
                <div class="relative">
                    <a href="{{ route('catalog.show', $item->product) }}">
                        <div class="h-52 bg-gray-700 overflow-hidden">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gracia-primary/20 to-gracia-secondary/20">
                                    <span class="text-5xl font-extrabold text-gracia-primary/40 select-none">H</span>
                                </div>
                            @endif
                        </div>
                    </a>
                    <!-- Remove from wishlist button -->
                    <button @click="remove()" :disabled="loading" class="absolute top-3 right-3 w-9 h-9 bg-gray-800/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-md hover:bg-red-50 transition-colors disabled:opacity-50" aria-label="Remover de favoritos">
                        <svg x-show="!loading" class="w-5 h-5 text-gracia-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
                <div class="p-5">
                    @if($item->product->category)
                        <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full mb-2 bg-gracia-secondary-light/30 text-teal-700">
                            {{ $item->product->category->name }}
                        </span>
                    @endif
                    <a href="{{ route('catalog.show', $item->product) }}">
                        <h3 class="font-bold text-white mb-1 hover:text-gracia-primary transition-colors">{{ $item->product->name }}</h3>
                    </a>
                    <p class="text-gray-400 text-sm mb-3 line-clamp-2">{{ $item->product->description }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gracia-primary-dark">₡{{ number_format($item->product->price, 0, ',', '.') }}</span>
                        <form action="{{ route('cart.add', $item->product) }}" method="POST" x-data="addToCart('{{ route('cart.add', $item->product) }}')" @submit="submit($event)">
                            @csrf
                            <button type="submit" :disabled="loading" :aria-label="'Agregar ' + @js($item->product->name) + ' al carrito'" class="w-10 h-10 rounded-xl bg-gracia-primary/10 text-gracia-primary hover:bg-gracia-primary hover:text-white flex items-center justify-center transition-colors disabled:opacity-50">
                                <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                <svg x-show="loading" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @else
        <div class="text-center py-20">
            <div class="w-24 h-24 bg-gracia-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gracia-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">No tienes favoritos aún</h3>
            <p class="text-gray-400 mb-6">Explora nuestro catálogo y guarda los productos que te gusten</p>
            <a href="{{ route('catalog.index') }}" class="btn-hakesa">Ver Catálogo</a>
        </div>
        @endif
    </div>
</section>
@endsection
