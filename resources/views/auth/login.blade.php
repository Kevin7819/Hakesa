@extends('layouts.public')

@section('title', 'Iniciar Sesión - Gracia Creativa')

@section('content')
<section class="min-h-screen flex items-center justify-center py-20 bg-gray-800">
    <div class="w-full max-w-md px-4">
        <div class="bg-gray-800 rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <img src="{{ asset('Gracia_Creativa_Logo_withou_background.png') }}" alt="Gracia Creativa" width="288" height="auto" class="w-72 h-auto mx-auto mb-4 object-contain">
                <h1 class="text-2xl font-bold text-white">Iniciar Sesión</h1>
                <p class="text-gray-400 mt-1">Bienvenido de vuelta</p>
            </div>

            @if(session('status'))
                <div class="bg-green-900/30 text-green-400 border border-green-700/50 p-3 rounded-xl mb-4 text-sm">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-xl text-white placeholder-gray-300/70 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent transition-all"
                        placeholder="tu@email.com">
                    @error('email')<p class="mt-1 text-sm text-red-400 font-semibold drop-shadow-sm">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Contraseña</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-2.5 bg-gray-700 border border-gray-600 rounded-xl text-white placeholder-gray-300/70 focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent transition-all"
                            placeholder="Tu contraseña">
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gracia-primary">
                            <svg id="password-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1 text-sm text-red-400 font-semibold drop-shadow-sm">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-500 text-gracia-primary focus:ring-gracia-primary">
                        <span class="text-sm text-gray-400">Recordarme</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-gracia-primary hover:text-gracia-primary-dark">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>

                <button type="submit" class="w-full btn-gracia py-3">Iniciar Sesión</button>
            </form>

            <p class="mt-6 text-center text-gray-400 text-sm">
                ¿No tenés cuenta? <a href="{{ route('register') }}" class="text-gracia-primary hover:text-gracia-primary-dark font-medium">Registrate</a>
            </p>
        </div>
    </div>
</section>

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
@endsection
