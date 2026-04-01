@extends('layouts.public')

@section('title', 'Mis Pedidos - Hakesa')

@section('content')
<section class="section-padding bg-hakesa-light">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Mis Pedidos</h1>

        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6">{{ session('success') }}</div>
        @endif

        @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
            <a href="{{ route('orders.show', $order) }}" class="card-hakesa p-5 block group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-mono font-bold text-gray-900 group-hover:text-hakesa-pink transition-colors">{{ $order->order_number }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg text-gray-900">₡{{ number_format($order->total, 0, ',', '.') }}</p>
                        <x-order-status :status="$order->status" />
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">{{ $order->items_count }} producto(s)</p>
            </a>
            @endforeach
        </div>

        <div class="mt-6">{{ $orders->links() }}</div>
        @else
        <div class="card-hakesa text-center py-16">
            <div class="w-24 h-24 bg-hakesa-pink/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-hakesa-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No tenés pedidos aún</h3>
            <p class="text-gray-500 mb-6">Hacé tu primer pedido desde nuestro catálogo</p>
            <a href="{{ route('catalog.index') }}" class="btn-hakesa">Ver Catálogo</a>
        </div>
        @endif
    </div>
</section>
@endsection
