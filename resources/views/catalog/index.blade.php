@extends('layouts.public')

@section('title', 'Catálogo - Hakesa')
@section('meta-description', 'Explora nuestro catálogo de productos personalizados. Tazas, camisas, termos, stickers y más.')

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

        <!-- Search & Filters (AJAX) -->
        <form x-ref="filtrosForm" method="GET" action="{{ route('catalog.index') }}" id="filterForm" class="mb-8" x-data="catalogFilters">
            <div class="card-hakesa p-5">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre del producto..."
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink text-sm"
                            @input="debounceSubmit()">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Categoría</label>
                        <select name="category" @change="submit()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink text-sm">
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
                                @change="submit()"
                                class="w-1/2 px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink text-sm">
                            <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Máx"
                                @change="submit()"
                                class="w-1/2 px-3 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink text-sm">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Ordenar</label>
                        <select name="sort" @change="submit()" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink text-sm">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Más recientes</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-4">
                    <button type="button" @click="clearFilters()" class="px-6 py-2 border border-gray-200 rounded-xl text-gray-500 hover:text-gray-700 hover:border-gray-300 text-sm font-medium transition-colors">
                        Limpiar filtros
                    </button>
                    <!-- Loading indicator -->
                    <div x-show="loading" x-cloak class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Filtrando...
                    </div>
                </div>
            </div>
        </form>

        <!-- Results info -->
        <div id="results-info">
            @if(request()->has('search') || request()->has('category') || request()->has('price_min') || request()->has('price_max'))
                <p class="text-sm text-gray-500 mb-4">{{ $products->total() }} resultado(s) encontrado(s)</p>
            @endif
        </div>

        <!-- Products Grid (replaced via AJAX) -->
        <div id="products-grid">
            @include('catalog._products_grid', ['products' => $products])
        </div>

        <!-- Pagination (replaced via AJAX) -->
        <div id="products-pagination" class="mt-10">
            {{ $products->links() }}
        </div>
    </div>
</section>
@endsection
