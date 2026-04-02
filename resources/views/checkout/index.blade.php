@extends('layouts.public')

@section('title', 'Checkout - Hakesa')

@section('content')
<section class="section-padding bg-hakesa-light">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Finalizar Pedido</h1>

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-start gap-3" role="alert">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Datos de contacto (read-only) -->
                <div class="card-hakesa p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Datos de Contacto</h2>
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                        @csrf

                        <input type="hidden" name="customer_name" value="{{ $user->name }}">
                        <input type="hidden" name="customer_email" value="{{ $user->email }}">
                        <input type="hidden" name="customer_phone" value="{{ $user->phone }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                                <input type="text" value="{{ $user->name }}" readonly tabindex="-1"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" value="{{ $user->email }}" readonly tabindex="-1"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <input type="tel" value="{{ $user->phone ?? 'No registrado' }}" readonly tabindex="-1"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 cursor-not-allowed">
                            </div>
                        </div>

                    <!-- Personalización por producto -->
                    @if($cart->items->count() > 0)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="text-base font-bold text-gray-900 mb-4">Personalización de Productos</h3>
                        <div class="space-y-5">
                            @foreach($cart->items as $item)
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="w-12 h-12 bg-white rounded-lg flex-shrink-0 overflow-hidden border border-gray-200">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <x-product-placeholder size="sm" class="w-12 h-12 rounded-lg" />
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 text-sm">{{ $item->product->name }}</p>
                                        <p class="text-xs text-gray-500">x{{ $item->quantity }} — ₡{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-4 h-4 text-hakesa-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                                        ¿Deseas personalizar este producto?
                                    </span>
                                </label>
                                <textarea name="customizations[{{ $item->id }}]" rows="2"
                                    class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-hakesa-pink focus:border-transparent resize-none"
                                    placeholder="Describe tu personalización aquí...">{{ old('customizations.' . $item->id, $item->customization) }}</textarea>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                        <!-- Notas generales -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas del pedido (opcional)</label>
                            <textarea name="notes" id="notes" rows="2"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink"
                                placeholder="Instrucciones especiales, preferencias, etc.">{{ old('notes') }}</textarea>
                        </div>

                        <div class="bg-hakesa-teal/5 border border-hakesa-teal/20 rounded-xl p-4 mt-6 flex items-start gap-3">
                            <svg class="w-5 h-5 text-hakesa-teal flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm text-gray-600">Una vez realizado el pedido nos contactaremos por WhatsApp para coordinar el diseño y el pago 🇨🇷</p>
                        </div>

                        <button type="submit" class="w-full btn-hakesa py-4 text-lg mt-4">
                            Confirmar Pedido
                        </button>
                    </form>
                </div>
            </div>

            <!-- Summary -->
            <div>
                <div class="card-hakesa p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Resumen del Pedido</h2>
                    <div class="divide-y divide-gray-100">
                        @foreach($cart->items as $item)
                        <div class="py-3 flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-500">x{{ $item->quantity }}</p>
                            </div>
                            <p class="font-semibold text-gray-900 text-sm">₡{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>
                    <hr class="my-4">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="text-2xl font-extrabold text-hakesa-pink">₡{{ number_format($cart->total, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('cart.index') }}" class="block text-center text-sm text-hakesa-pink hover:text-hakesa-pink-dark mt-4">← Volver al carrito</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
