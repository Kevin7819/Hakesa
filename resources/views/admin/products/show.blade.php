@extends('admin.layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-4xl space-y-6">
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-hakesa-pink hover:text-hakesa-pink-dark"><i class="fas fa-arrow-left"></i> Volver</a>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="md:flex">
            <div class="md:w-2/5 bg-gray-50 flex items-center justify-center p-8">
                @if($product->image)<img src="{{ asset('storage/'.$product->image) }}" alt="" class="max-w-full h-auto rounded-xl">@else<div class="w-48 h-48 rounded-xl flex items-center justify-center bg-gradient-to-br from-hakesa-pink/20 to-hakesa-teal/20"><span class="text-5xl font-extrabold text-hakesa-pink/40 select-none">H</span></div>@endif
            </div>
            <div class="md:w-3/5 p-8">
                <div class="flex justify-between items-start mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                    @if($product->is_active)<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Activo</span>@else<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Inactivo</span>@endif
                </div>
                @if($product->category)<span class="inline-block px-2.5 py-1 bg-hakesa-teal-light/30 text-teal-700 text-xs font-semibold rounded-full mb-4">{{ $product->category->name }}</span>@endif
                @if($product->description)<p class="text-gray-600 mb-6">{{ $product->description }}</p>@endif
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded-xl"><p class="text-gray-400 text-sm">Precio</p><p class="text-2xl font-bold text-hakesa-pink-dark">₡{{ number_format($product->price, 0, ',', '.') }}</p></div>
                    <div class="bg-gray-50 p-4 rounded-xl"><p class="text-gray-400 text-sm">Stock</p><p class="text-2xl font-bold text-gray-900">{{ $product->stock }} <span class="text-sm font-normal text-gray-400">uds</span></p></div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-hakesa text-sm"><i class="fas fa-edit mr-2"></i>Editar</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">@csrf @method('DELETE')<button @click.prevent="confirm('¿Eliminar?') && $el.closest('form').submit()" class="px-5 py-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 font-semibold text-sm"><i class="fas fa-trash mr-2"></i>Eliminar</button></form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
