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
                        class="w-full px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-xl text-white placeholder-gray-300/70 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent transition-all"
                        placeholder="admin@graciacreativa.com">
                    @error('email')<p class="mt-1 text-sm text-red-400 font-semibold drop-shadow-sm">{{ $message }}</p>@enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Contraseña</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-2.5 bg-gray-800 border border-gray-600 rounded-xl text-white placeholder-gray-300/70 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent transition-all"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gracia-primary">
                            <svg id="password-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1 text-sm text-red-400 font-semibold drop-shadow-sm">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-500 text-gracia-primary focus:ring-gracia-primary bg-gray-700">
                        <span class="text-sm text-gray-300">Recordarme</span>
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
<script>
function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
}
</script>
</html>
