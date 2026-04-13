@extends('admin.layouts.app')

@section('title', 'Pedidos')

@section('content')
<div class="space-y-6">
    <div><h1 class="text-2xl font-bold text-white">Gestión de Pedidos</h1><p class="text-gray-400 mt-1">Revisa y administra los pedidos</p></div>
    @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>@endif
    <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-900"><tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Orden</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase"></th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-900">
                    <td class="px-6 py-4 font-mono text-white text-sm">{{ $order->order_number }}</td>
                    <td class="px-6 py-4"><p class="text-white">{{ $order->customer_name }}</p><p class="text-sm text-gray-400">{{ $order->customer_email }}</p></td>
                    <td class="px-6 py-4 text-white font-semibold">₡{{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php $c=['pending'=>'bg-yellow-100 text-yellow-700','confirmed'=>'bg-blue-100 text-blue-700','in_progress'=>'bg-gracia-secondary/10 text-teal-700','completed'=>'bg-green-100 text-green-700','sent'=>'bg-gracia-primary/10 text-pink-700','cancelled'=>'bg-red-100 text-red-700']; $l=['pending'=>'Pendiente','confirmed'=>'Confirmado','in_progress'=>'En Proceso','completed'=>'Completado','sent'=>'Enviado','cancelled'=>'Cancelado']; @endphp
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $c[$order->status]??'bg-gray-100 text-gray-400' }}">{{ $l[$order->status]??ucfirst($order->status) }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4"><a href="{{ route('admin.orders.show', $order) }}" class="text-gracia-primary hover:text-gracia-primary-dark text-sm font-medium"><i class="fas fa-eye mr-1"></i>Ver</a></td>
                </tr>
                @empty<tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No hay pedidos</td></tr>@endforelse
            </tbody>
        </table>
        @if($orders->hasPages())<div class="px-6 py-4 border-t border-gray-700">{{ $orders->links() }}</div>@endif
    </div>
</div>
@endsection
