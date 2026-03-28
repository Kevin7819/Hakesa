@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500 mt-1">Resumen general de tu negocio</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-hakesa-pink/10 rounded-xl flex items-center justify-center"><i class="fas fa-box text-hakesa-pink text-lg"></i></div>
                <span class="text-xs text-gray-400 uppercase">Productos</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['totalProducts'] ?? 0 }}</p>
            <p class="text-gray-400 text-sm mt-1">Total en catálogo</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-hakesa-yellow/10 rounded-xl flex items-center justify-center"><i class="fas fa-clock text-hakesa-yellow-dark text-lg"></i></div>
                <span class="text-xs text-gray-400 uppercase">Pendientes</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['pendingOrders'] ?? 0 }}</p>
            <p class="text-gray-400 text-sm mt-1">Pedidos por atender</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-hakesa-teal/10 rounded-xl flex items-center justify-center"><i class="fas fa-check-circle text-hakesa-teal text-lg"></i></div>
                <span class="text-xs text-gray-400 uppercase">Completados</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['completedThisMonth'] ?? 0 }}</p>
            <p class="text-gray-400 text-sm mt-1">Este mes</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-hakesa-gold/10 rounded-xl flex items-center justify-center"><i class="fas fa-users text-hakesa-gold-dark text-lg"></i></div>
                <span class="text-xs text-gray-400 uppercase">Clientes</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['totalClients'] ?? 0 }}</p>
            <p class="text-gray-400 text-sm mt-1">Usuarios registrados</p>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Últimos Pedidos</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-hakesa-pink hover:text-hakesa-pink-dark text-sm font-medium">Ver todos →</a>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50"><tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Orden</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase"></th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentOrders ?? [] as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900 font-mono">{{ $order->order_number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $order->customer_name ?? ($order->user?->name ?? 'N/A') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">₡{{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @php $colors = ['pending'=>'bg-yellow-100 text-yellow-700','confirmed'=>'bg-blue-100 text-blue-700','in_progress'=>'bg-hakesa-teal/10 text-hakesa-teal-dark','completed'=>'bg-green-100 text-green-700','sent'=>'bg-hakesa-pink/10 text-hakesa-pink-dark','cancelled'=>'bg-red-100 text-red-700']; @endphp
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $colors[$order->status] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-400">{{ $order->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4"><a href="{{ route('admin.orders.show', $order) }}" class="text-hakesa-pink hover:text-hakesa-pink-dark text-sm font-medium">Ver</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No hay pedidos aún</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
