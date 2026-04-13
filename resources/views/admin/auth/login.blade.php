<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gracia Creativa Admin - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700 p-8">
            <div class="text-center mb-8">
                <img src="{{ asset('Gracia_Creativa_Logo_withou_background.png') }}" alt="Gracia Creativa" width="200" height="auto" class="w-52 h-auto mx-auto mb-4 object-contain">
                <h1 class="text-2xl font-bold text-white">Gracia Creativa Admin</h1>
                <p class="text-gray-400 mt-1">Panel de Administración</p>
            </div>

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent"
                        placeholder="admin@graciacreativa.com">
                    @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Contraseña</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent"
                        placeholder="••••••••">
                    @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-gracia-primary focus:ring-gracia-primary">
                        <span class="text-sm text-gray-400">Recordarme</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 bg-gracia-primary hover:bg-gracia-primary-dark text-white font-semibold rounded-xl transition-colors text-base">Iniciar Sesión</button>
            </form>

            <div class="mt-6 text-center">
                <a href="/" class="text-sm text-gray-400 hover:text-gracia-primary transition-colors">← Volver al sitio público</a>
            </div>
        </div>
    </div>
</body>
</html>
