@extends('layouts.public')

@section('title', 'Mi Carrito - Hakesa')

@section('content')
<section class="section-padding bg-hakesa-light">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Mi Carrito</h1>

        @if($cart->items->count() > 0)
        <div class="space-y-6">
            <!-- Items -->
            <div class="card-hakesa divide-y divide-gray-100">
                @foreach($cart->items as $item)
                <div class="p-5 flex gap-4" data-cart-item>
                    <!-- Image -->
                    <div class="w-20 h-20 bg-gray-100 rounded-xl flex-shrink-0 overflow-hidden">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-hakesa-pink/20 to-hakesa-teal/20">
                                <span class="text-lg font-bold text-hakesa-pink/40">H</span>
                            </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900">{{ $item->product->name }}</h3>
                        <p class="text-hakesa-pink-dark font-semibold">₡{{ number_format($item->product->price, 0, ',', '.') }}</p>
                        @if($item->customization)
                            <p class="text-sm text-gray-500 mt-1">Personalización: {{ $item->customization }}</p>
                        @endif
                    </div>

                    <!-- Quantity (auto-save) -->
                    <div class="flex items-center gap-3"
                         x-data="cartItem('{{ route('cart.update', $item) }}', '{{ route('cart.remove', $item) }}', {{ $item->quantity }})">
                        <div class="flex items-center gap-2">
                            <input type="number" x-model="quantity" min="1" max="99"
                                @change.debounce.500ms="updateQty()"
                                class="w-16 px-3 py-1.5 border border-gray-200 rounded-lg text-center text-sm focus:outline-none focus:ring-2 focus:ring-hakesa-pink">
                            <span x-show="updating" x-cloak class="text-xs text-gray-400">...</span>
                        </div>
                        <button @click="remove()" :disabled="removing" class="text-sm text-red-400 hover:text-red-600 disabled:opacity-50">
                            <span x-show="!removing">Eliminar</span>
                            <span x-show="removing" x-cloak>...</span>
                        </button>
                    </div>

                    <!-- Subtotal -->
                    <div class="text-right">
                        <p class="font-bold text-gray-900">₡{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div class="card-hakesa p-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-500" id="cart-item-count">{{ $cart->item_count }} producto{{ $cart->item_count != 1 ? 's' : '' }}</span>
                    <span class="text-2xl font-bold text-gray-900" id="cart-total">₡{{ number_format($cart->total, 0, ',', '.') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 items-center">
                    <div x-data="cartClear('{{ route('cart.clear') }}')">
                        <button @click="clear()" :disabled="loading" class="text-sm text-red-400 hover:text-red-600 font-medium disabled:opacity-50 underline-offset-2 hover:underline">
                            <span x-show="!loading">Vaciar carrito</span>
                            <span x-show="loading" x-cloak>Vaciando...</span>
                        </button>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="flex-1 btn-hakesa py-3 text-center justify-center">
                        Proceder al Checkout
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="card-hakesa text-center py-16">
            <div class="w-24 h-24 bg-hakesa-pink/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-hakesa-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Tu carrito está vacío</h3>
            <p class="text-gray-500 mb-6">Agregá productos desde nuestro catálogo</p>
            <a href="{{ route('catalog.index') }}" class="btn-hakesa">Ver Catálogo</a>
        </div>
        @endif
    </div>
</section>
@endsection
