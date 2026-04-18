{{-- Section: Announcements / Events
     Muestra únicamente anuncios visibles (activos y no expirados)
     Si no hay anuncios → no renderiza nada --}}
@if($announcements->count() > 0)
<section class="py-20 lg:py-28 bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-gracia-primary/10 text-gracia-primary rounded-full text-sm font-semibold mb-4">
                Próximos Eventos
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                No te pierdas nuestras actividades
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
                Mantente al día con eventos, talleres y promociones especiales que tenemos para ti.
            </p>
        </div>

        {{-- Grid: 1 col mobile, 2 col md, 3 col lg --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($announcements as $announcement)
            <div class="bg-gray-800 border border-gray-600 rounded-2xl overflow-hidden hover:-translate-y-1 transition-transform duration-300 shadow-lg">
                {{-- Image: 16:9 aspect ratio --}}
                <div class="aspect-video relative overflow-hidden">
                    @if($announcement->image)
                        <img 
                            src="{{ asset('storage/' . $announcement->image) }}" 
                            alt="{{ $announcement->title }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gracia-primary to-gracia-secondary flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    
                    {{-- Badge: "Evento" --}}
                    <span class="absolute top-4 left-4 px-3 py-1 bg-gracia-secondary text-white text-xs font-semibold rounded-full">
                        Evento
                    </span>
                </div>

                {{-- Content --}}
                <div class="p-6">
                    {{-- Title --}}
                    <h3 class="text-xl font-bold text-white mb-2">
                        {{ $announcement->title }}
                    </h3>

                    {{-- Description --}}
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                        {{ $announcement->description }}
                    </p>

                    {{-- Date --}}
                    <div class="flex items-center gap-2 text-gracia-primary mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-medium">
                            {{ \Carbon\Carbon::parse($announcement->event_date)->locale('es')->format('d M Y') }}
                        </span>
                    </div>

                    {{-- Location (optional) --}}
                    @if($announcement->location)
                    <div class="flex items-center gap-2 text-gray-400 mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm">{{ $announcement->location }}</span>
                    </div>
                    @endif

                    {{-- Button: Solo si existe link --}}
                    @if($announcement->link)
                    <a 
                        href="{{ $announcement->link }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 text-gracia-primary hover:text-gracia-primary-dark font-medium text-sm transition-colors"
                    >
                        Más información
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif