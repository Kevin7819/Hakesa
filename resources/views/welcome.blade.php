@extends('layouts.public')

@section('title', 'Arte y Diseño Hakesa - Personalización en Costa Rica')

@section('content')

<!-- ═══════════════════════════════════════════════════════════════
     HERO SECTION
     ═══════════════════════════════════════════════════════════════ -->
<section id="inicio" class="relative overflow-hidden">
    <div class="gradient-hero min-h-[90vh] flex items-center">
        <!-- Decorative shapes -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-hakesa-yellow/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text -->
                <div class="text-white animate-fade-in-up">
                    <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium mb-6">
                        Tu taller de confianza en Costa Rica
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                        Personalización<br>
                        que deja <span class="text-hakesa-yellow">huella</span>
                    </h1>
                    <p class="text-xl text-white/80 mb-8 max-w-lg">
                        Sublimación, corte láser, vinil y más. Transformamos tus ideas en productos únicos.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#catalogo" class="btn-hakesa bg-white text-hakesa-pink hover:bg-gray-100 shadow-xl">
                            Ver Catálogo
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="#contacto" class="btn-hakesa-outline border-white text-white hover:bg-white hover:text-hakesa-pink">
                            Hacer Pedido
                        </a>
                    </div>
                </div>

                <!-- Hero image / logo -->
                <div class="hidden lg:flex justify-center items-center">
                    <div class="relative">
                        <div class="w-80 h-80 bg-white/10 backdrop-blur-sm rounded-3xl flex items-center justify-center animate-float">
                            <img src="{{ asset('logo.png') }}" alt="Hakesa Logo" class="w-64 h-64 object-contain" onerror="this.parentElement.innerHTML='<div class=\'text-white text-center\'><div class=\'text-8xl font-extrabold opacity-30\'>H</div><p class=\'text-xl mt-4 opacity-50\'>HAKESA</p></div>'">
                        </div>
                        <!-- Floating badges -->
                        <div class="absolute -top-4 -right-4 bg-hakesa-yellow text-gray-900 px-4 py-2 rounded-xl font-bold text-sm shadow-lg">
                            Costa Rica
                        </div>
                        <div class="absolute -bottom-4 -left-4 bg-white text-hakesa-pink px-4 py-2 rounded-xl font-bold text-sm shadow-lg">
                            +500 clientes
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave separator -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════
     SERVICES SECTION
     ═══════════════════════════════════════════════════════════════ -->
<section id="servicios" class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-hakesa-pink/10 text-hakesa-pink rounded-full text-sm font-semibold mb-4">Nuestros Servicios</span>
            <h2 class="section-title">¿Qué podemos crear para ti?</h2>
            <p class="section-subtitle">Descubre todas las posibilidades de personalización que ofrecemos</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Sublimación -->
            <div class="card-hakesa p-8 text-center group">
                <div class="w-20 h-20 bg-hakesa-pink/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-hakesa-pink/20 transition-colors">
                    <svg class="w-10 h-10 text-hakesa-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Sublimación</h3>
                <p class="text-gray-500">Tazas, camisas, termos, alfombras y más. Tu diseño único en cualquier producto.</p>
            </div>

            <!-- Corte Láser -->
            <div class="card-hakesa p-8 text-center group">
                <div class="w-20 h-20 bg-hakesa-teal/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-hakesa-teal/20 transition-colors">
                    <svg class="w-10 h-10 text-hakesa-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Corte Láser</h3>
                <p class="text-gray-500">Corte preciso en madera, acrílico y metal. Decoración personalizada de alta calidad.</p>
            </div>

            <!-- Vinil -->
            <div class="card-hakesa p-8 text-center group">
                <div class="w-20 h-20 bg-hakesa-yellow/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-hakesa-yellow/20 transition-colors">
                    <svg class="w-10 h-10 text-hakesa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Vinil</h3>
                <p class="text-gray-500">Stickers personalizados, calcomanías y gráficos de alta calidad duradera.</p>
            </div>

            <!-- Envíos -->
            <div class="card-hakesa p-8 text-center group">
                <div class="w-20 h-20 bg-hakesa-gold/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-hakesa-gold/20 transition-colors">
                    <svg class="w-10 h-10 text-hakesa-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3">Envíos CR</h3>
                <p class="text-gray-500">Te enviamos tu pedido a cualquier lugar del país mediante Correos de Costa Rica.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════
     CAROUSEL / CATÁLOGO SECTION
     ═══════════════════════════════════════════════════════════════ -->
<section id="catalogo" class="section-padding bg-hakesa-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-hakesa-teal/10 text-hakesa-teal rounded-full text-sm font-semibold mb-4">Catálogo</span>
            <h2 class="section-title">Productos Destacados</h2>
            <p class="section-subtitle">Explora algunos de nuestros productos más populares</p>
        </div>

        @if($products->count() > 0)
        <!-- Carrusel manual con Alpine.js -->
        <div class="relative" x-data="carousel">
            <div class="carousel-container rounded-2xl">
                <div class="carousel-track" :style="`transform: translateX(-${current * 100}%)`">
                    @foreach($products->chunk(3) as $chunk)
                    <div class="carousel-slide px-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($chunk as $product)
                            <div class="card-hakesa overflow-hidden">
                                <div class="h-56 bg-gray-100 overflow-hidden">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-hakesa-pink/10 to-hakesa-teal/10">
                                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    @if($product->category)
                                        <span class="inline-block px-2.5 py-1 bg-hakesa-teal/10 text-hakesa-teal text-xs font-semibold rounded-full mb-3">{{ $product->category->name }}</span>
                                    @endif
                                    <h3 class="text-lg font-bold mb-2">{{ $product->name }}</h3>
                                    <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-2xl font-bold text-hakesa-pink">₡{{ number_format($product->price, 0, ',', '.') }}</span>
                                        <a href="#contacto" class="btn-hakesa text-sm px-4 py-2">Consultar</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Navigation arrows -->
            @if($products->count() > 3)
            <button @click="prev()" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-hakesa-pink hover:text-white transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="next()" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-hakesa-pink hover:text-white transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Dots -->
            <div class="flex justify-center gap-2 mt-8">
                <template x-for="i in total" :key="i">
                    <button @click="goTo(i - 1)" :class="current === i - 1 ? 'bg-hakesa-pink w-8' : 'bg-gray-300 w-3'" class="h-3 rounded-full transition-all duration-300"></button>
                </template>
            </div>
            @endif
        </div>
        @else
        <!-- Empty state -->
        <div class="text-center py-20">
            <div class="w-24 h-24 bg-hakesa-pink/10 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-hakesa-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Próximamente más productos</h3>
            <p class="text-gray-500 mb-6">Estamos preparando nuestro catálogo. ¡Vuelve pronto!</p>
            <a href="#contacto" class="btn-hakesa">Contáctanos</a>
        </div>
        @endif
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════
     CÓMO FUNCIONA SECTION
     ═══════════════════════════════════════════════════════════════ -->
<section class="section-padding bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-hakesa-gold/10 text-hakesa-gold-dark rounded-full text-sm font-semibold mb-4">Proceso</span>
            <h2 class="section-title">¿Cómo Funciona?</h2>
            <p class="section-subtitle">En solo 3 pasos tienes tu producto personalizado</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            <!-- Connection line (desktop) -->
            <div class="hidden md:block absolute top-16 left-1/4 right-1/4 h-0.5 bg-gradient-to-r from-hakesa-pink via-hakesa-teal to-hakesa-yellow"></div>

            <!-- Step 1 -->
            <div class="text-center relative">
                <div class="w-32 h-32 gradient-hakesa rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-hakesa-pink/20 relative z-10">
                    <span class="text-4xl font-extrabold text-white">1</span>
                </div>
                <h3 class="text-xl font-bold mb-3">Elige tu Producto</h3>
                <p class="text-gray-500">Explora nuestro catálogo y selecciona el producto que más te gusta.</p>
            </div>

            <!-- Step 2 -->
            <div class="text-center relative">
                <div class="w-32 h-32 bg-hakesa-teal rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-hakesa-teal/20 relative z-10">
                    <span class="text-4xl font-extrabold text-white">2</span>
                </div>
                <h3 class="text-xl font-bold mb-3">Personalízalo</h3>
                <p class="text-gray-500">Envíanos tu diseño, texto o idea y lo personalizamos a tu gusto.</p>
            </div>

            <!-- Step 3 -->
            <div class="text-center relative">
                <div class="w-32 h-32 bg-hakesa-yellow rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-hakesa-yellow/30 relative z-10">
                    <span class="text-4xl font-extrabold text-gray-900">3</span>
                </div>
                <h3 class="text-xl font-bold mb-3">Lo recibes en casa</h3>
                <p class="text-gray-500">Te lo enviamos a cualquier lugar de Costa Rica con Correos.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════
     TESTIMONIOS SECTION
     ═══════════════════════════════════════════════════════════════ -->
<section id="testimonios" class="section-padding bg-hakesa-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-hakesa-pink/10 text-hakesa-pink rounded-full text-sm font-semibold mb-4">Testimonios</span>
            <h2 class="section-title">Lo que dicen nuestros clientes</h2>
            <p class="section-subtitle">Miles de personas confían en Hakesa para sus proyectos de personalización</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Testimonio 1 -->
            <div class="card-hakesa p-8">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-hakesa-yellow" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6 italic">"Excelente calidad en las tazas sublimadas. El diseño quedó perfecto y el envío fue rapidísimo. ¡Totalmente recomendados!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-hakesa-pink/20 rounded-full flex items-center justify-center text-hakesa-pink font-bold">MR</div>
                    <div>
                        <p class="font-semibold">María Rodríguez</p>
                        <p class="text-gray-400 text-sm">San José, CR</p>
                    </div>
                </div>
            </div>

            <!-- Testimonio 2 -->
            <div class="card-hakesa p-8">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-hakesa-yellow" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6 italic">"Pedí stickers personalizados para mi negocio y quedaron increíbles. La calidad del vinil es premium. ¡Volveré a pedir!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-hakesa-teal/20 rounded-full flex items-center justify-center text-hakesa-teal font-bold">CL</div>
                    <div>
                        <p class="font-semibold">Carlos López</p>
                        <p class="text-gray-400 text-sm">Heredia, CR</p>
                    </div>
                </div>
            </div>

            <!-- Testimonio 3 -->
            <div class="card-hakesa p-8">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-5 h-5 text-hakesa-yellow" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6 italic">"El corte láser en madera para mi proyecto de decoración quedó espectacular. Precisión y acabado perfecto. ¡Gracias Hakesa!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-hakesa-gold/20 rounded-full flex items-center justify-center text-hakesa-gold-dark font-bold">AV</div>
                    <div>
                        <p class="font-semibold">Ana Vargas</p>
                        <p class="text-gray-400 text-sm">Alajuela, CR</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════
     CTA / CONTACTO SECTION
     ═══════════════════════════════════════════════════════════════ -->
<section id="contacto" class="gradient-hero section-padding relative overflow-hidden">
    <!-- Decorative -->
    <div class="absolute inset-0">
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-hakesa-yellow/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-6">¿Listo para crear algo único?</h2>
        <p class="text-xl text-white/80 mb-10 max-w-2xl mx-auto">
            Contáctanos por WhatsApp y descubre cómo podemos hacer realidad tu idea. Cotización sin compromiso.
        </p>
        <a href="https://wa.me/50688888888" target="_blank" class="inline-flex items-center gap-3 bg-green-500 hover:bg-green-600 text-white px-10 py-5 rounded-2xl font-bold text-lg shadow-2xl shadow-green-500/30 transition-all duration-300 hover:-translate-y-1">
            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.445 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.881-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            Escríbenos por WhatsApp
        </a>
        <p class="mt-6 text-white/60 text-sm">Respuesta en menos de 24 horas</p>
    </div>
</section>

@endsection
