@extends('layouts.public')

@section('title', 'Checkout - Hakesa')

@section('content')
<section class="section-padding bg-hakesa-light">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Finalizar Pedido</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form -->
            <div class="lg:col-span-2">
                <div class="card-hakesa p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Datos de Contacto y Envío</h2>
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $user->name) }}" required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink">
                                @error('customer_name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', $user->email) }}" required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink">
                                @error('customer_email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                                <input type="tel" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', $user->phone) }}" required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink"
                                    placeholder="+506 8888-8888">
                                @error('customer_phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-1">Dirección de envío</label>
                                <textarea name="customer_address" id="customer_address" rows="2"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink"
                                    placeholder="Dirección completa para envío">{{ old('customer_address') }}</textarea>
                                @error('customer_address')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notas del pedido (opcional)</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink"
                                    placeholder="Instrucciones especiales, preferencias, etc.">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="w-full btn-hakesa py-4 text-lg mt-6">
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
