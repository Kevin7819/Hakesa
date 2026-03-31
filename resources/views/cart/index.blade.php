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
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-gray-900 truncate">{{ $item->product->name }}</h3>
                        <p class="text-hakesa-pink-dark font-semibold">₡{{ number_format($item->product->price, 0, ',', '.') }}</p>
                        @if($item->customization)
                            <p class="text-sm text-gray-500 mt-1 truncate">Personalización: {{ $item->customization }}</p>
                        @endif
                    </div>

                    <!-- Quantity (auto-update) -->
                    <div class="flex items-center gap-3 flex-shrink-0"
                         x-data="cartItem('{{ route('cart.update', $item) }}', '{{ route('cart.remove', $item) }}')">
                        <div class="flex items-center gap-2">
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                @input="autoUpdate($event)"
                                @change="autoUpdate($event)"
                                class="w-16 px-3 py-1.5 border border-gray-200 rounded-lg text-center text-sm focus:outline-none focus:ring-2 focus:ring-hakesa-pink transition-shadow">
                            <!-- Loading indicator -->
                            <span x-show="updating" x-cloak class="text-hakesa-pink">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </span>
                        </div>
                        <button @click="remove()" :disabled="removing" class="text-sm text-gray-400 hover:text-red-500 transition-colors disabled:opacity-50" aria-label="Eliminar producto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>

                    <!-- Subtotal -->
                    <div class="text-right flex-shrink-0">
                        <p class="font-bold text-gray-900" data-subtotal>₡{{ number_format($item->subtotal, 0, ',', '.') }}</p>
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
                <div class="flex items-center justify-between">
                    <div x-data="cartClear('{{ route('cart.clear') }}')">
                        <button @click="clear()" :disabled="loading" class="text-sm text-gray-400 hover:text-red-500 transition-colors disabled:opacity-50">
                            <span x-show="!loading">Vaciar carrito</span>
                            <span x-show="loading" x-cloak>Vaciando...</span>
                        </button>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn-hakesa py-3 px-8 text-center">
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
