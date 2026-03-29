@extends('layouts.public')

@section('title', 'Iniciar Sesión - Hakesa')

@section('content')
<section class="min-h-screen flex items-center justify-center py-20 bg-hakesa-light">
    <div class="w-full max-w-md px-4">
        <div class="card-hakesa p-8">
            <div class="text-center mb-8">
                <img src="{{ asset('Hakesa_without_background.png') }}" alt="Hakesa" class="w-16 h-16 mx-auto mb-4 object-contain">
                <h1 class="text-2xl font-bold text-gray-900">Iniciar Sesión</h1>
                <p class="text-gray-500 mt-1">Bienvenido de vuelta</p>
            </div>

            @if(session('status'))
                <div class="bg-green-50 text-green-700 p-3 rounded-xl mb-4 text-sm">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink focus:border-transparent transition-all"
                        placeholder="tu@email.com">
                    @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink focus:border-transparent transition-all"
                        placeholder="Tu contraseña">
                    @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-hakesa-pink focus:ring-hakesa-pink">
                        <span class="text-sm text-gray-600">Recordarme</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-hakesa-pink hover:text-hakesa-pink-dark">¿Olvidaste tu contraseña?</a>
                    @endif
                </div>

                <button type="submit" class="w-full btn-hakesa py-3">Iniciar Sesión</button>
            </form>

            <p class="mt-6 text-center text-gray-500 text-sm">
                ¿No tenés cuenta? <a href="{{ route('register') }}" class="text-hakesa-pink hover:text-hakesa-pink-dark font-medium">Registrate</a>
            </p>
        </div>
    </div>
</section>
@endsection
