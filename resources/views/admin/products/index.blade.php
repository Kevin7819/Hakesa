@extends('admin.layouts.app')

@section('title', 'Productos')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div><h1 class="text-2xl font-bold text-gray-900">Gestión de Productos</h1><p class="text-gray-500 mt-1">Administra tu catálogo</p></div>
        <a href="{{ route('admin.products.create') }}" class="btn-hakesa text-sm"><i class="fas fa-plus"></i> Nuevo Producto</a>
    </div>
    @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex items-center gap-3"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>@endif
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50"><tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Imagen</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Categoría</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Acciones</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">@if($product->image)<img src="{{ asset('storage/'.$product->image) }}" alt="" class="w-14 h-14 object-cover rounded-xl">@else<div class="w-14 h-14 rounded-xl flex items-center justify-center bg-gradient-to-br from-hakesa-pink/20 to-hakesa-teal/20"><span class="text-sm font-extrabold text-hakesa-pink/40 select-none">H</span></div>@endif</td>
                    <td class="px-6 py-4 text-gray-900 font-medium">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->category?->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-gray-900 font-semibold">₡{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->stock }}</td>
                    <td class="px-6 py-4">@if($product->is_active)<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Activo</span>@else<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Inactivo</span>@endif</td>
                    <td class="px-6 py-4"><div class="flex items-center gap-2">
                        <a href="{{ route('admin.products.show', $product) }}" class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 hover:bg-blue-100"><i class="fas fa-eye text-sm"></i></a>
                        <a href="{{ route('admin.products.edit', $product) }}" class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600 hover:bg-yellow-100"><i class="fas fa-edit text-sm"></i></a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">@csrf @method('DELETE')<button @click.prevent="confirm('¿Eliminar?') && $el.closest('form').submit()" class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100"><i class="fas fa-trash text-sm"></i></button></form>
                    </div></td>
                </tr>
                @empty<tr><td colspan="7" class="px-6 py-12 text-center text-gray-400">No hay productos</td></tr>@endforelse
            </tbody>
        </table>
        @if($products->hasPages())<div class="px-6 py-4 border-t border-gray-100">{{ $products->links() }}</div>@endif
    </div>
</div>
@endsection
