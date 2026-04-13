@extends('layouts.public')

@section('title', 'Catálogo - Gracia Creativa')
@section('meta-description', 'Explora nuestro catálogo de productos personalizados. Tazas, camisas, termos, stickers y más.')

@section('content')
<section class="section-padding bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <span class="inline-block px-4 py-1.5 bg-gracia-primary/10 text-gracia-primary rounded-full text-sm font-semibold mb-4">Catálogo</span>
            <h1 class="section-title">Nuestros Productos</h1>
            <p class="section-subtitle">Explora todos los productos que podemos personalizar para ti</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Layout: Sidebar + Product Grid -->
        <div class="flex flex-col lg:flex-row gap-8" x-data="{ ...catalogFilters(), mobileDrawerOpen: false }">

            {{-- Mobile Filter Toggle --}}
            <div class="lg:hidden mb-4">
                <button type="button" @click="mobileDrawerOpen = true"
                    class="w-full px-4 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-xl font-medium flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filtros
                </button>
            </div>

            {{-- Mobile Drawer Overlay --}}
            <div x-show="mobileDrawerOpen" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click.self="mobileDrawerOpen = false"
                 class="fixed inset-0 bg-black/50 z-40 lg:hidden">
            </div>

            {{-- Mobile Drawer Panel --}}
            <div x-show="mobileDrawerOpen" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed top-0 left-0 h-full w-80 bg-gray-800 z-50 p-6 overflow-y-auto lg:hidden shadow-2xl">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">Filtros</h2>
                    <button @click="mobileDrawerOpen = false" class="w-10 h-10 rounded-full bg-gray-700 hover:bg-gray-600 flex items-center justify-center transition-colors" aria-label="Cerrar filtros">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @include('catalog._filter_fields', ['maxPrice' => $maxPrice])
            </div>

            {{-- Desktop Sidebar --}}
            <aside class="hidden lg:block w-72 flex-shrink-0">
                <div class="sticky top-24 card-gracia p-5">
                    <h2 class="text-lg font-bold text-white mb-4">Filtros</h2>
                    @include('catalog._filter_fields', ['maxPrice' => $maxPrice])
                </div>
            </aside>

            {{-- Product Grid --}}
            <div class="flex-1 min-w-0">
                <!-- Results info -->
                <div id="results-info">
                    @if(request()->has('search') || request()->has('category') || request()->has('price_min') || request()->has('price_max'))
                        <p class="text-sm text-gray-400 mb-4">{{ $products->total() }} resultado(s) encontrado(s)</p>
                    @else
                        <p class="text-sm text-gray-400 mb-4">{{ $products->total() }} producto{{ $products->total() !== 1 ? 's' : '' }} disponible{{ $products->total() !== 1 ? 's' : '' }}</p>
                    @endif
                </div>

                <!-- Products Grid (replaced via AJAX) -->
                <div id="products-grid">
                    @include('catalog._products_grid', ['products' => $products])
                </div>

                <!-- Pagination (replaced via AJAX) -->
                <div id="products-pagination" class="mt-10">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
