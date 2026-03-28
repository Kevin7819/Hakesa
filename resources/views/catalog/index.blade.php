@extends('layouts.public')

@section('title', 'Catálogo - Hakesa')

@section('content')
<section class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <span class="inline-block px-4 py-1.5 bg-hakesa-pink/10 text-hakesa-pink rounded-full text-sm font-semibold mb-4">Catálogo</span>
            <h1 class="section-title">Nuestros Productos</h1>
            <p class="section-subtitle">Explora todos los productos que podemos personalizar para ti</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Search & Filters -->
        <form method="GET" action="{{ route('catalog.index') }}" id="filterForm" class="mb-8">
            <div class="card-hakesa p-5">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre del producto..."
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink text-sm"
                            oninput="clearTimeout(this._t); this._t=setTimeout(()=>this.form.submit(), 500)">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Categoría</label>
                        <select name="category" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink text-sm">
                            <option value="">Todas</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Precio (₡)</label>
                        <div class="flex gap-2">
                            <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Mín"
                                onchange="this.form.submit()"
                                class="w-1/2 px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink text-sm">
                            <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Máx"
                                onchange="this.form.submit()"
                                class="w-1/2 px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink text-sm">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Ordenar</label>
                        <select name="sort" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink text-sm">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Más recientes</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('catalog.index') }}" class="px-6 py-2 border border-gray-200 rounded-xl text-gray-500 hover:text-gray-700 hover:border-gray-300 text-sm font-medium transition-colors">
                        Limpiar filtros
                    </a>
                </div>
            </div>
        </form>

        <!-- Results info -->
        @if(request()->has('search') || request()->has('category') || request()->has('price_min') || request()->has('price_max'))
            <p class="text-sm text-gray-500 mb-4">{{ $products->total() }} resultado(s) encontrado(s)</p>
        @endif

        <!-- Products Grid -->
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
                        <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full mb-2 bg-hakesa-teal/10 text-hakesa-teal">
                            {{ $product->category->name }}
                        </span>
                    @endif
                    <a href="{{ route('catalog.show', $product) }}">
                        <h3 class="font-bold text-gray-900 mb-1 hover:text-hakesa-pink transition-colors">{{ $product->name }}</h3>
                    </a>
                    <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-hakesa-pink">₡{{ number_format($product->price, 0, ',', '.') }}</span>
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-10 h-10 rounded-xl bg-hakesa-pink/10 text-hakesa-pink hover:bg-hakesa-pink hover:text-white flex items-center justify-center transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-10">
            {{ $products->links() }}
        </div>
        @else
        <div class="text-center py-20">
            <div class="w-24 h-24 bg-hakesa-pink/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-hakesa-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No se encontraron productos</h3>
            <p class="text-gray-500 mb-4">Probá con otros filtros de búsqueda</p>
            <a href="{{ route('catalog.index') }}" class="btn-hakesa">Ver todos los productos</a>
        </div>
        @endif
    </div>
</section>
@endsection
