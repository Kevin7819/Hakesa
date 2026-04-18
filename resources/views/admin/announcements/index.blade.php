@extends('admin.layouts.app')

@section('title', 'Anuncios y Eventos')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Anuncios y Eventos</h1>
            <p class="text-gray-400 mt-1">Gestiona los eventos y anuncios del sitio público</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="btn-gracia inline-flex items-center gap-2 w-fit">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo Anuncio
        </a>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="p-4 bg-gracia-accent/10 border border-gracia-accent/30 text-gracia-accent rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Imagen</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Expira</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($announcements as $announcement)
                    <tr class="hover:bg-gray-700/30 transition-colors">
                        {{-- Image --}}
                        <td class="px-6 py-4">
                            @if($announcement->image)
                                <div class="w-16 h-12 rounded-lg overflow-hidden bg-gray-700">
                                    <img src="{{ asset('storage/' . $announcement->image) }}" alt="" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-16 h-12 rounded-lg bg-gradient-to-br from-gracia-primary/30 to-gracia-secondary/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </td>

                        {{-- Title --}}
                        <td class="px-6 py-4">
                            <div class="text-white font-medium max-w-xs truncate">{{ $announcement->title }}</div>
                            <div class="text-gray-400 text-sm mt-0.5 max-w-xs truncate">{{ $announcement->description }}</div>
                        </td>

                         {{-- Event Date --}}
                         <td class="px-6 py-4">
                             <span class="text-white text-sm font-medium">
                                 {{ \Carbon\Carbon::parse($announcement->event_date)->locale('es')->format('d M Y') }}
                             </span>
                         </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            @if($announcement->isExpired())
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-600 text-gray-300">
                                    Expirado
                                </span>
                            @elseif($announcement->is_active)
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-gracia-accent text-gray-900">
                                    Activo
                                </span>
                            @else
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full bg-gracia-secondary text-white">
                                    Inactivo
                                </span>
                            @endif
                        </td>

                         {{-- Expires At --}}
                         <td class="px-6 py-4">
                             @if($announcement->expires_at)
                                 <span class="text-gray-200 text-sm">
                                     {{ \Carbon\Carbon::parse($announcement->expires_at)->locale('es')->format('d M Y H:i') }}
                                 </span>
                             @else
                                 <span class="text-gray-300 text-sm">Nunca</span>
                             @endif
                         </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Toggle (AJAX con Alpine) --}}
                                <button 
                                    x-data="{ 
                                        loading: false,
                                        toggle() {
                                            this.loading = true;
                                            fetch('{{ route('admin.announcements.toggle', $announcement) }}', {
                                                method: 'PATCH',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Content-Type': 'application/json'
                                                }
                                            }).then(() => window.location.reload())
                                        }
                                    }"
                                    @click="toggle()"
                                    :disabled="loading"
                                    class="p-2 rounded-lg transition-colors {{ $announcement->is_active ? 'bg-gracia-accent/10 text-gracia-accent hover:bg-gracia-accent/20' : 'bg-gray-600/10 text-gray-400 hover:bg-gray-600/20' }}"
                                    title="{{ $announcement->is_active ? 'Desactivar' : 'Activar' }}"
                                >
                                    <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <svg x-show="loading" x-cloak class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                </button>

                                 {{-- Edit --}}
                                 <a 
                                     href="{{ route('admin.announcements.edit', $announcement) }}"
                                     class="p-2 text-gray-200 hover:text-gracia-primary hover:bg-gracia-primary/10 rounded-lg transition-colors"
                                     title="Editar"
                                 >
                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                     </svg>
                                 </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit"
                                        onclick="return confirm('¿Estás seguro de eliminar este anuncio?')"
                                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors"
                                        title="Eliminar"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white mb-2">No hay anuncios</h3>
                                <p class="text-gray-400 mb-4">Crea el primer anuncio para mostrar en el sitio público</p>
                                <a href="{{ route('admin.announcements.create') }}" class="btn-gracia">
                                    Crear Anuncio
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($announcements->hasPages())
        <div class="flex justify-center">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection