@extends('layouts.public')

@section('title', 'Pedido ' . $order->order_number . ' - Gracia Creativa')

@section('content')
<section class="section-padding bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back -->
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-gracia-primary hover:text-gracia-primary-dark mb-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Volver a mis pedidos
        </a>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 p-6 rounded-xl mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-green-700 whitespace-pre-line">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        <div class="card-hakesa p-6 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white font-mono">{{ $order->order_number }}</h1>
                    <p class="text-gray-400 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <x-order-status :status="$order->status" size="lg" />
            </div>
        </div>

        <!-- Items -->
        <div class="card-hakesa overflow-hidden mb-6">
            <div class="p-5 border-b border-gray-100">
                <h2 class="font-bold text-white">Productos</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($order->items as $item)
                <div class="p-5 flex justify-between items-start">
                    <div>
                        <p class="font-medium text-white">{{ $item->product_name }}</p>
                        <p class="text-sm text-gray-400">{{ $item->quantity }} x ₡{{ number_format($item->price, 0, ',', '.') }}</p>
                        @if($item->customization)
                            <p class="text-sm text-teal-700 mt-1">Personalización: {{ $item->customization }}</p>
                        @endif
                    </div>
                    <p class="font-bold text-white">₡{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Summary -->
        <div class="card-hakesa p-6">
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-400">Subtotal</span>
                    <span class="text-white">₡{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Envío</span>
                    <span class="text-white">{{ $order->shipping_cost > 0 ? '₡' . number_format($order->shipping_cost, 0, ',', '.') : 'Por definir' }}</span>
                </div>
                <hr>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-white">Total</span>
                    <span class="text-2xl font-extrabold text-gracia-primary">₡{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($order->notes)
            <div class="mt-6 pt-6 border-t border-gray-100">
                <p class="text-sm text-gray-400 font-medium mb-1">Notas:</p>
                <p class="text-gray-300">{{ $order->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
