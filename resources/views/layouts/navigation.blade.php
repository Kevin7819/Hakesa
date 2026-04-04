<nav x-data="{ mobileMenu: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('catalog.index') }}">
                        <img src="{{ asset('Hakesa_logo.webp') }}" alt="Hakesa" width="38" height="36" class="h-9 w-auto">
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.index')">
                        {{ __('Catálogo') }}
                    </x-nav-link>
                    <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')">
                        {{ __('Carrito') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Desktop User Links (no dropdown) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:space-x-4">
                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-500 hover:text-hakesa-pink transition-colors">
                    {{ __('Perfil') }}
                </a>
                <span class="text-gray-300">|</span>
                <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-hakesa-pink transition-colors">
                        {{ __('Cerrar Sesión') }}
                    </button>
                </form>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="mobileMenu = ! mobileMenu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-hakesa-pink hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-hakesa-pink transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenu, 'inline-flex': ! mobileMenu }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! mobileMenu, 'inline-flex': mobileMenu }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': mobileMenu, 'hidden': ! mobileMenu}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('catalog.index')" :active="request()->routeIs('catalog.index')">
                {{ __('Catálogo') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')">
                {{ __('Carrito') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-ref="logoutFormMobile">
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}"
                            @click.prevent="$refs.logoutFormMobile.submit()">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
