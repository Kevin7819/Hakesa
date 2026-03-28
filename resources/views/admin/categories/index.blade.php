@extends('admin.layouts.app')

@section('title', 'Categorías')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestión de Categorías</h1>
            <p class="text-gray-500 mt-1">Organiza tus productos por categorías</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-hakesa text-sm">
            <i class="fas fa-plus"></i> Nueva Categoría
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i>{{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Productos</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Orden</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $cat)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $cat->name }}</td>
                    <td class="px-6 py-4 text-gray-500 text-sm font-mono">{{ $cat->slug }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $cat->products_count }}</td>
                    <td class="px-6 py-4">
                        @if($cat->is_active)<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Activa</span>
                        @else<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Inactiva</span>@endif
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $cat->sort_order }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.categories.edit', $cat) }}" class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600 hover:bg-yellow-100 transition-colors"><i class="fas fa-edit text-sm"></i></a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline">@csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Eliminar esta categoría?')" class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500 hover:bg-red-100 transition-colors"><i class="fas fa-trash text-sm"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No hay categorías</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
