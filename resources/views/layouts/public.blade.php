<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Arte y Diseño Hakesa - Personalización en Costa Rica')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    <!-- ═══ Navbar ═══ -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100" x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('Hakesa_without_background.png') }}" alt="Hakesa" class="h-12 w-auto">
                    <span class="text-2xl font-bold text-gray-900">Hakesa</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="/" class="text-gray-600 hover:text-hakesa-pink font-medium transition-colors">Inicio</a>
                    <a href="{{ route('catalog.index') }}" class="text-gray-600 hover:text-hakesa-pink font-medium transition-colors">Catálogo</a>
                    <a href="/#contacto" class="text-gray-600 hover:text-hakesa-pink font-medium transition-colors">Contacto</a>

                    @auth
                        <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-hakesa-pink transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                            <span x-show="$store.cart.count > 0" x-text="$store.cart.count" x-cloak class="absolute -top-2 -right-2 w-5 h-5 bg-hakesa-pink text-white text-xs rounded-full flex items-center justify-center font-bold"></span>
                        </a>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 text-gray-600 hover:text-hakesa-pink font-medium transition-colors">
                                {{ auth()->user()->name }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-hakesa-light hover:text-hakesa-pink">Mis Pedidos</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-hakesa-light hover:text-hakesa-pink">Mi Perfil</a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-red-50">Cerrar Sesión</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-hakesa-pink font-medium transition-colors">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn-hakesa text-sm">Registrarse</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <button @click="open = !open" class="md:hidden p-2 text-gray-600 hover:text-hakesa-pink">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-cloak x-transition class="md:hidden bg-white border-t border-gray-100 shadow-lg">
            <div class="px-4 py-4 space-y-2">
                <a href="/" @click="open = false" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-hakesa-light hover:text-hakesa-pink font-medium">Inicio</a>
                <a href="{{ route('catalog.index') }}" @click="open = false" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-hakesa-light hover:text-hakesa-pink font-medium">Catálogo</a>
                <a href="/#contacto" @click="open = false" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-hakesa-light hover:text-hakesa-pink font-medium">Contacto</a>
                @auth
                    <a href="{{ route('cart.index') }}" @click="open = false" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-hakesa-light hover:text-hakesa-pink font-medium">Carrito</a>
                    <a href="{{ route('orders.index') }}" @click="open = false" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-hakesa-light hover:text-hakesa-pink font-medium">Mis Pedidos</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 rounded-lg text-red-500 hover:bg-red-50 font-medium">Cerrar Sesión</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" @click="open = false" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-hakesa-light hover:text-hakesa-pink font-medium">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" @click="open = false" class="block btn-hakesa text-center mt-2">Registrarse</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ═══ Main Content ═══ -->
    <main class="pt-20">
        @yield('content')
    </main>

    <!-- ═══ Footer ═══ -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 section-padding">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('Hakesa_without_background.png') }}" alt="Hakesa" class="h-10 w-auto brightness-0 invert">
                    </div>
                    <p class="text-gray-400 max-w-md mb-6">
                        Personalización que deja huella. Sublimación, corte láser, vinil y más.
                        Tu taller de confianza en Costa Rica.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-hakesa-pink flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-hakesa-pink flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                        </a>
                        <a href="https://wa.me/50689926464" class="w-10 h-10 rounded-full bg-gray-800 hover:bg-green-500 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.445 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.881-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Servicios</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li><a href="#servicios" class="hover:text-hakesa-pink transition-colors">Sublimación</a></li>
                        <li><a href="#servicios" class="hover:text-hakesa-pink transition-colors">Corte Láser</a></li>
                        <li><a href="#servicios" class="hover:text-hakesa-pink transition-colors">Vinil y Stickers</a></li>
                        <li><a href="#servicios" class="hover:text-hakesa-pink transition-colors">Envíos a todo CR</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contacto</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-hakesa-pink flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            hakesa2023@gmail.com
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-hakesa-pink flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            +506 8992 6464
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-hakesa-pink flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            736G+Q6 Heredia, Sarapiquí, Costa Rica
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Arte y Diseño Hakesa. Todos los derechos reservados.</p>
                <a href="{{ route('login') }}" class="text-gray-500 hover:text-hakesa-pink text-sm transition-colors">Acceso Clientes</a>
            </div>
        </div>
    </footer>

    {{-- ═══ Cart count init + Toast UI ═══ --}}
    @auth
    <script>window.__cartCount = {{ auth()->user()->cart ? auth()->user()->cart->item_count : 0 }};</script>
    @endauth
    <div x-data x-cloak
         class="fixed bottom-6 right-6 z-[9999] space-y-2 max-w-sm w-full pointer-events-none"
         style="display: none;"
         x-init="$nextTick(() => { $el.style.display = ''; })">
        <template x-for="toast in $store.toasts.items" :key="toast.id">
            <div x-show="true"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 :class="toast.type === 'success' ? 'bg-green-500' : 'bg-red-500'"
                 class="pointer-events-auto text-white px-5 py-3 rounded-xl shadow-xl font-medium text-sm flex items-center gap-2">
                <template x-if="toast.type === 'success'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </template>
                <span x-text="toast.message"></span>
            </div>
        </template>
    </div>
</body>
</html>
