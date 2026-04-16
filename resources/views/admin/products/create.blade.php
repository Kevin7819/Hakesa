@extends('admin.layouts.app')

@section('title', 'Crear Producto')

@section('content')
<div class="max-w-3xl space-y-6">
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-gracia-primary hover:text-gracia-primary-dark"><i class="fas fa-arrow-left"></i> Volver a productos</a>
    <h1 class="text-2xl font-bold text-white">Crear Nuevo Producto</h1>
    <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700 p-6">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">@csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-medium text-gray-300 mb-1">Nombre *</label><input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent" placeholder="Ej: Taza personalizada">@error('name')<p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-300 mb-1">Categoría</label><select name="category_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent"><option value="">Seleccionar...</option>@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach</select>@error('category_id')<p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-300 mb-1">Precio (₡) *</label><input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent">@error('price')<p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-300 mb-1">Stock *</label><input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent">@error('stock')<p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>@enderror</div>
                <div><label class="block text-sm font-medium text-gray-300 mb-1">Tipo de servicio</label><select name="service_type" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent"><option value="">Seleccionar...</option><option value="sublimacion" {{ old('service_type')=='sublimacion'?'selected':'' }}>Sublimación</option><option value="laser" {{ old('service_type')=='laser'?'selected':'' }}>Láser</option><option value="vinil" {{ old('service_type')=='vinil'?'selected':'' }}>Vinil</option></select></div>
                <div><label class="block text-sm font-medium text-gray-300 mb-1">Estado</label><select name="is_active" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent"><option value="1" {{ old('is_active','1')=='1'?'selected':'' }}>Activo</option><option value="0" {{ old('is_active')=='0'?'selected':'' }}>Inactivo</option></select></div>
            </div>
            <div class="mt-6"><label class="block text-sm font-medium text-gray-300 mb-1">Descripción</label><textarea name="description" rows="4" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent">{{ old('description') }}</textarea></div>
            <div class="mt-6"><label class="block text-sm font-medium text-gray-300 mb-1">Imagen</label><input type="file" name="image" accept="image/*" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gracia-primary/20 file:text-gracia-primary file:font-medium file:cursor-pointer">@error('image')<p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>@enderror</div>
            <div class="mt-8 flex justify-end gap-4"><a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 bg-gray-700 text-gray-300 rounded-xl hover:bg-gray-600">Cancelar</a><button type="submit" class="btn-gracia">Crear Producto</button></div>
        </form>
    </div>
</div>
@endsection
