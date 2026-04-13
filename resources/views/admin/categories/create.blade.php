@extends('admin.layouts.app')

@section('title', 'Crear Categoría')

@section('content')
<div class="max-w-2xl space-y-6">
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-2 text-gracia-primary hover:text-gracia-primary-dark"><i class="fas fa-arrow-left"></i> Volver</a>
    <h1 class="text-2xl font-bold text-white">Crear Categoría</h1>
    <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST">@csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nombre *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary" placeholder="Ej: Sublimación">
                @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-300 mb-1">Descripción</label>
                <textarea name="description" id="description" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary">{{ old('description') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-300 mb-1">Orden</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary">
                </div>
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-300 mb-1">Estado</label>
                    <select name="is_active" id="is_active" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Activa</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactiva</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2.5 bg-gray-700 text-gray-300 rounded-xl hover:bg-gray-200">Cancelar</a>
                <button type="submit" class="btn-hakesa">Crear Categoría</button>
            </div>
        </form>
    </div>
</div>
@endsection
