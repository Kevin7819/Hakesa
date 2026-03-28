@extends('admin.layouts.app')

@section('title', 'Pedido ' . $order->order_number)

@section('content')
<div class="max-w-5xl space-y-6">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-hakesa-pink hover:text-hakesa-pink-dark"><i class="fas fa-arrow-left"></i> Volver</a>
    @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>@endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Información del Pedido</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div><p class="text-gray-400 text-sm">Número</p><p class="font-mono font-semibold text-gray-900">{{ $order->order_number }}</p></div>
                    <div><p class="text-gray-400 text-sm">Fecha</p><p class="text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p></div>
                    <div><p class="text-gray-400 text-sm">Cliente</p><p class="text-gray-900">{{ $order->customer_name }}</p></div>
                    <div><p class="text-gray-400 text-sm">Email</p><p class="text-gray-900">{{ $order->customer_email }}</p></div>
                    <div><p class="text-gray-400 text-sm">Teléfono</p><p class="text-gray-900">{{ $order->customer_phone }}</p></div>
                    @if($order->customer_address)<div class="col-span-2"><p class="text-gray-400 text-sm">Dirección</p><p class="text-gray-900">{{ $order->customer_address }}</p></div>@endif
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100"><h2 class="font-bold text-gray-900">Productos</h2></div>
                <table class="w-full"><thead class="bg-gray-50"><tr><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Producto</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Precio</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cant.</th><th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subtotal</th></tr></thead>
                    <tbody class="divide-y divide-gray-100">@forelse($order->items as $item)<tr><td class="px-4 py-4 text-gray-900"><p class="font-medium">{{ $item->product_name }}</p>@if($item->customization)<p class="text-sm text-gray-400">{{ $item->customization }}</p>@endif</td><td class="px-4 py-4 text-gray-500">₡{{ number_format($item->price, 0, ',', '.') }}</td><td class="px-4 py-4 text-gray-500">{{ $item->quantity }}</td><td class="px-4 py-4 text-gray-900 font-semibold">₡{{ number_format($item->subtotal, 0, ',', '.') }}</td></tr>@empty<tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Sin productos</td></tr>@endforelse</tbody>
                </table>
            </div>
            @if($order->notes)<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6"><h2 class="font-bold text-gray-900 mb-2">Notas</h2><p class="text-gray-600">{{ $order->notes }}</p></div>@endif
        </div>
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Actualizar Estado</h2>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">@csrf @method('PATCH')
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink mb-4">
                        <option value="pending" {{ $order->status==='pending'?'selected':'' }}>Pendiente</option>
                        <option value="confirmed" {{ $order->status==='confirmed'?'selected':'' }}>Confirmado</option>
                        <option value="in_progress" {{ $order->status==='in_progress'?'selected':'' }}>En Proceso</option>
                        <option value="completed" {{ $order->status==='completed'?'selected':'' }}>Completado</option>
                        <option value="sent" {{ $order->status==='sent'?'selected':'' }}>Enviado</option>
                        <option value="cancelled" {{ $order->status==='cancelled'?'selected':'' }}>Cancelado</option>
                    </select>
                    <button type="submit" class="w-full btn-hakesa">Actualizar</button>
                </form>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Resumen</h2>
                <div class="space-y-3">
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="text-gray-900">₡{{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Envío</span><span class="text-gray-900">{{ $order->shipping_cost > 0 ? '₡'.number_format($order->shipping_cost, 0, ',', '.') : 'Por definir' }}</span></div>
                    <hr>
                    <div class="flex justify-between"><span class="font-bold text-gray-900">Total</span><span class="text-hakesa-pink font-bold text-xl">₡{{ number_format($order->total, 0, ',', '.') }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
