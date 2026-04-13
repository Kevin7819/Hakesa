<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" type="image/png" href="/Gracia_Creativa_Logo.png">
        <link rel="apple-touch-icon" href="/Gracia_Creativa_Logo.png">
    <title>@yield('title', 'Gracia Creativa Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-900 text-gray-100">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- ═══ Sidebar ═══ -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-gray-800 border-r border-gray-600 shadow-sm transform transition-transform duration-300"
        >
            <!-- Logo -->
            <div class="h-20 flex items-center gap-3 px-6 border-b border-gray-700">
                <img src="{{ asset('Gracia_Creativa_Logo.png') }}" alt="Gracia Creativa" width="43" height="40" class="h-10 w-auto">
                <div>
                    <span class="text-lg font-bold text-white">Gracia Creativa</span>
                    <span class="block text-xs text-gray-400">Panel de Administración</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="{{ request()->routeIs('admin.dashboard') ? 'bg-gracia-primary/10 text-gracia-primary font-semibold' : 'text-gray-400 hover:bg-gray-900 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors">
                    <i class="fas fa-chart-pie w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.products.index') }}" 
                   class="{{ request()->routeIs('admin.products.*') ? 'bg-gracia-primary/10 text-gracia-primary font-semibold' : 'text-gray-400 hover:bg-gray-900 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors">
                    <i class="fas fa-box-open w-5 text-center"></i>
                    <span>Productos</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="{{ request()->routeIs('admin.categories.*') ? 'bg-gracia-primary/10 text-gracia-primary font-semibold' : 'text-gray-400 hover:bg-gray-900 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors">
                    <i class="fas fa-tags w-5 text-center"></i>
                    <span>Categorías</span>
                </a>
                
                <a href="{{ route('admin.orders.index') }}" 
                   class="{{ request()->routeIs('admin.orders.*') ? 'bg-gracia-primary/10 text-gracia-primary font-semibold' : 'text-gray-400 hover:bg-gray-900 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors">
                    <i class="fas fa-shopping-bag w-5 text-center"></i>
                    <span>Pedidos</span>
                </a>

                <a href="{{ route('admin.comments.index') }}" 
                   class="{{ request()->routeIs('admin.comments.*') ? 'bg-gracia-primary/10 text-gracia-primary font-semibold' : 'text-gray-400 hover:bg-gray-900 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors">
                    <i class="fas fa-comments w-5 text-center"></i>
                    <span>Comentarios</span>
                </a>

                <div class="pt-4 mt-4 border-t border-gray-700">
                    <a href="/" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-gray-900 hover:text-gray-300 transition-colors">
                        <i class="fas fa-external-link-alt w-5 text-center"></i>
                        <span>Ver sitio público</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- ═══ Main Content ═══ -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Header -->
            <header class="h-20 bg-gray-800 border-b border-gray-600 flex items-center justify-between px-6">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-400 hover:text-gray-300">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <div class="flex items-center gap-4 ml-auto">
                    <span class="text-gray-400 hidden sm:block font-medium">{{ auth()->guard('admin')->user()?->name }}</span>
                    @if(auth()->guard('admin')->user()?->role)
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gracia-primary/10 text-gracia-primary">
                            {{ ucfirst(auth()->guard('admin')->user()->role) }}
                        </span>
                    @endif
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-xl bg-gray-100 text-gray-400 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
