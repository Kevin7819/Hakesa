@extends('admin.layouts.app')

@section('title', 'Editar Anuncio')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    {{-- Back link --}}
    <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center gap-2 text-gracia-primary hover:text-gracia-primary-dark">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a anuncios
    </a>

    <h1 class="text-2xl font-bold text-white">Editar Anuncio</h1>

    <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700 p-6">
        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Image Upload with Preview (Alpine.js) --}}
                <div x-data="imagePreview()">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Imagen</label>
                    <div class="flex items-start gap-6">
                        {{-- Preview (muestra la imagen actual o la nueva) --}}
                        <div class="w-40 h-28 bg-gray-900 rounded-xl border border-gray-600 overflow-hidden flex items-center justify-center shrink-0">
                            @if($announcement->image)
                                <template x-if="!imagePreviewUrl">
                                    <img src="{{ asset('storage/' . $announcement->image) }}" class="w-full h-full object-cover">
                                </template>
                            @else
                                <template x-if="!imagePreviewUrl">
                                    <div class="text-center p-4">
                                        <svg class="w-8 h-8 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs text-gray-500">Sin imagen</span>
                                    </div>
                                </template>
                            @endif
                            <template x-if="imagePreviewUrl">
                                <img :src="imagePreviewUrl" class="w-full h-full object-cover">
                            </template>
                        </div>

                        {{-- Input + Remove checkbox --}}
                        <div class="flex-1">
                            <input 
                                type="file" 
                                name="image" 
                                accept="image/*"
                                @change="previewImage($event)"
                                class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gracia-primary/20 file:text-gracia-primary file:font-medium file:cursor-pointer focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent"
                            >
                            @if($announcement->image)
                            <div class="flex items-center gap-2 mt-3">
                                <input type="checkbox" name="remove_image" id="remove_image" value="1" 
                                    class="w-4 h-4 rounded bg-gray-900 border-gray-600 text-red-500 focus:ring-red-500 focus:ring-offset-gray-800 accent-red-500">
                                <label for="remove_image" class="text-sm text-gray-400 hover:text-red-400 transition-colors">Eliminar imagen actual</label>
                            </div>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG, GIF, WebP. Máximo 5MB.</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Title + Description --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div x-data="{ chars: {{ strlen(old('title', $announcement->title)) }}, max: 60 }">
                        <label class="block text-sm font-medium text-gray-300 mb-1">
                            Título <span class="text-gray-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="title" 
                            value="{{ old('title', $announcement->title) }}" 
                            maxlength="60"
                            required
                            @input="chars = $event.target.value.length"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent"
                            placeholder="Ej: Taller de sublimación"
                        >
                        <p class="text-right text-xs mt-1" :class="chars >= max ? 'text-red-400' : 'text-gray-500'">
                            <span x-text="chars"></span>/<span x-text="max"></span>
                        </p>
                        @error('title')
                            <p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ chars: {{ strlen(old('description', $announcement->description)) }}, max: 120 }">
                        <label class="block text-sm font-medium text-gray-300 mb-1">
                            Descripción <span class="text-gray-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="description" 
                            value="{{ old('description', $announcement->description) }}" 
                            maxlength="120"
                            required
                            @input="chars = $event.target.value.length"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent"
                            placeholder="Ej: Aprende a personalizar tazas"
                        >
                        <p class="text-right text-xs mt-1" :class="chars >= max ? 'text-red-400' : 'text-gray-500'">
                            <span x-text="chars"></span>/<span x-text="max"></span>
                        </p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Event Date + Location --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">
                            Fecha del evento <span class="text-gray-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="date" 
                                name="event_date" 
                                value="{{ old('event_date', $announcement->event_date?->format('Y-m-d')) }}" 
                                required
                                style="color-scheme: dark;"
                                class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent pl-10"
                            >
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <style>
                            input[type="date"]::-webkit-datetime-edit,
                            input[type="date"]::-webkit-datetime-edit-text,
                            input[type="date"]::-webkit-datetime-edit-month-field,
                            input[type="date"]::-webkit-datetime-edit-day-field,
                            input[type="date"]::-webkit-datetime-edit-year-field {
                                color: #ffffff !important;
                            }
                            input[type="date"]::-webkit-calendar-picker-indicator {
                                filter: invert(0.7);
                            }
                        </style>
                        @error('event_date')
                            <p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">
                            Ubicación
                        </label>
                        <input 
                            type="text" 
                            name="location" 
                            value="{{ old('location', $announcement->location) }}" 
                            maxlength="100"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent"
                            placeholder="Ej: San José, Costa Rica"
                        >
                        @error('location')
                            <p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Link --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">
                        Enlace externo
                    </label>
<input 
                            type="url" 
                            name="link" 
                            value="{{ old('link', $announcement->link) }}" 
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent"
                            placeholder="https://example.com/evento"
                        >
                    <p class="text-xs text-gray-500 mt-1">URL completa (opcional). Ej: Evento en Facebook, Google Forms, etc.</p>
                    @error('link')
                        <p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Toggle is_active + Expires At --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Toggle is_active (vertical: label above switch) --}}
                    <div x-data="{ isActive: {{ old('is_active', $announcement->is_active) ? 'true' : 'false' }} }">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Estado</label>
                        <div class="flex items-center gap-3">
                            <button 
                                type="button"
                                @click="isActive = !isActive"
                                class="relative inline-flex h-8 w-14 items-center rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:ring-offset-2 focus:ring-offset-gray-800"
                                :class="isActive ? 'bg-gracia-primary' : 'bg-gray-600'"
                            >
                                <input type="hidden" name="is_active" :value="isActive ? 1 : 0">
                                <span 
                                    class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white shadow transform transition-transform duration-300"
                                    :class="isActive ? 'translate-x-6' : 'translate-x-0'"
                                ></span>
                            </button>
                            <span class="text-sm font-medium" :class="isActive ? 'text-gracia-primary' : 'text-gray-400'">
                                <span x-text="isActive ? 'Activo' : 'Inactivo'"></span>
                            </span>
                        </div>
                    </div>

                    {{-- Expires At --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">
                            Expira el
                        </label>
                        <div class="relative">
                            <input 
                                type="datetime-local" 
                                name="expires_at" 
                                value="{{ old('expires_at', $announcement->expires_at?->format('Y-m-d\TH:i')) }}" 
                                style="color-scheme: dark;"
                                class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent pl-10"
                            >
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <style>
                            input[type="datetime-local"]::-webkit-datetime-edit,
                            input[type="datetime-local"]::-webkit-datetime-edit-text,
                            input[type="datetime-local"]::-webkit-datetime-edit-month-field,
                            input[type="datetime-local"]::-webkit-datetime-edit-day-field,
                            input[type="datetime-local"]::-webkit-datetime-edit-year-field,
                            input[type="datetime-local"]::-webkit-datetime-edit-hour-field,
                            input[type="datetime-local"]::-webkit-datetime-edit-minute-field {
                                color: #ffffff !important;
                            }
                            input[type="datetime-local"]::-webkit-calendar-picker-indicator {
                                filter: invert(0.7);
                            }
                        </style>
                        <p class="text-xs text-gray-500 mt-1">Dejar vacío para nunca expirar</p>
                        @error('expires_at')
                            <p class="mt-1 text-sm text-red-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="mt-8 flex justify-end gap-4">
                <a 
                    href="{{ route('admin.announcements.index') }}" 
                    class="px-6 py-2.5 bg-gray-700 text-gray-300 rounded-xl hover:bg-gray-600 transition-colors"
                >
                    Cancelar
                </a>
                <button type="submit" class="btn-gracia">
                    Actualizar Anuncio
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Alpine.js component for image preview --}}
<script>
function imagePreview() {
    return {
        imagePreviewUrl: null,
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreviewUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.imagePreviewUrl = null;
            }
        }
    };
}
</script>
@endsection