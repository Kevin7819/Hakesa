@extends('admin.layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<div class="max-w-2xl space-y-6">
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-2 text-gracia-primary hover:text-gracia-primary-dark"><i class="fas fa-arrow-left"></i> Volver</a>
    <h1 class="text-2xl font-bold text-white">Editar Categoría</h1>
    <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700 p-6">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">@csrf @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nombre *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent">
                @error('name')<p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-300 mb-1">Descripción</label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-300 mb-1">Orden</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent">
                </div>
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-300 mb-1">Estado</label>
                    <select name="is_active" id="is_active" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent">
                        <option value="1" {{ old('is_active', $category->is_active) ? 'selected' : '' }}>Activa</option>
                        <option value="0" {{ !old('is_active', $category->is_active) ? 'selected' : '' }}>Inactiva</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2.5 bg-gray-700 text-gray-300 rounded-xl hover:bg-gray-600">Cancelar</a>
                <button type="submit" class="btn-gracia">Actualizar Categoría</button>
            </div>
        </form>
    </div>
</div>
@endsection
