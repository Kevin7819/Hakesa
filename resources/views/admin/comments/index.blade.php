@extends('admin.layouts.app')

@section('title', 'Moderación de Comentarios')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Moderación de Comentarios</h1>
        <p class="text-gray-400 mt-1">Aprueba o rechaza comentarios de los clientes</p>
    </div>

    @if(session('success'))
        <div class="bg-green-900/30 border border-green-700/50 text-green-400 p-4 rounded-xl">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    {{-- Comentarios Pendientes --}}
    <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-yellow-900/30 border-b border-yellow-700/50">
            <h2 class="text-lg font-semibold text-yellow-400">
                <i class="fas fa-clock mr-2"></i>Pendientes ({{ $pending->total() }})
            </h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($pending as $comment)
                <div class="px-6 py-4 flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-gracia-primary/20 rounded-full flex items-center justify-center text-gracia-primary font-bold text-sm">
                                {{ strtoupper(substr($comment->user?->name ?? 'XX', 0,2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-white">{{ $comment->user?->name ?? 'Usuario eliminado' }}</p>
                                <p class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="text-gray-400 italic">"{{ $comment->content }}"</p>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                        <button type="submit" class="px-3 py-2 bg-green-900/30 text-green-400 border border-green-700/50 rounded-lg hover:bg-green-900/50 transition text-sm font-medium" title="Aprobar">
                            <i class="fas fa-check mr-1"></i>Aprobar
                        </button>
                        <button type="submit" class="px-3 py-2 bg-red-900/30 text-red-400 border border-red-700/50 rounded-lg hover:bg-red-900/50 transition text-sm font-medium" title="Rechazar">
                            <i class="fas fa-times mr-1"></i>Rechazar
                        </button>
                        </form>
                        <form action="{{ route('admin.comments.reject', $comment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-medium" title="Rechazar">
                                <i class="fas fa-times mr-1"></i>Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-400">
                    <i class="fas fa-check-circle text-4xl mb-3 text-green-300"></i>
                    <p>No hay comentarios pendientes</p>
                </div>
            @endforelse
        </div>
        @if($pending->hasPages())
            <div class="px-6 py-4 border-t border-gray-700">{{ $pending->links() }}</div>
        @endif
    </div>

    {{-- Comentarios Aprobados --}}
    <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-green-900/30 border-b border-green-700/50">
            <h2 class="text-lg font-semibold text-green-400">
                <i class="fas fa-check-circle mr-2"></i>Aprobados ({{ $approved->total() }})
            </h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($approved as $comment)
                <div class="px-6 py-4 flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-gracia-primary/20 rounded-full flex items-center justify-center text-gracia-primary font-bold text-sm">
                                {{ strtoupper(substr($comment->user?->name ?? 'XX', 0,2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-white">{{ $comment->user?->name ?? 'Usuario eliminado' }}</p>
                                <p class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="text-gray-400 italic">"{{ $comment->content }}"</p>
                    </div>
                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('¿Eliminar este comentario?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 bg-gray-700 text-gray-400 rounded-lg hover:bg-red-50 hover:text-red-500 transition text-sm" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-400">No hay comentarios aprobados</div>
            @endforelse
        </div>
        @if($approved->hasPages())
            <div class="px-6 py-4 border-t border-gray-700">{{ $approved->links() }}</div>
        @endif
    </div>

    {{-- Comentarios Rechazados --}}
    <div class="bg-gray-800 rounded-2xl shadow-sm border border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-red-900/30 border-b border-red-700/50">
            <h2 class="text-lg font-semibold text-red-400">
                <i class="fas fa-times-circle mr-2"></i>Rechazados ({{ $rejected->total() }})
            </h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($rejected as $comment)
                <div class="px-6 py-4 flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 font-bold text-sm">
                                {{ strtoupper(substr($comment->user?->name ?? 'XX', 0,2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-white">{{ $comment->user?->name ?? 'Usuario eliminado' }}</p>
                                <p class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="text-gray-400 italic line-through">"{{ $comment->content }}"</p>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition text-sm" title="Aprobar">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('¿Eliminar este comentario?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 bg-gray-700 text-gray-400 rounded-lg hover:bg-red-50 hover:text-red-500 transition text-sm" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-400">No hay comentarios rechazados</div>
            @endforelse
        </div>
        @if($rejected->hasPages())
            <div class="px-6 py-4 border-t border-gray-700">{{ $rejected->links() }}</div>
        @endif
    </div>
</div>
@endsection
