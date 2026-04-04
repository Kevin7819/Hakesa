@extends('layouts.public')

@section('title', 'Crear Cuenta - Hakesa')

@section('content')
<section class="min-h-screen flex items-center justify-center py-20 bg-hakesa-light">
    <div class="w-full max-w-md px-4">
        <div class="card-hakesa p-8">
            <div class="text-center mb-8">
                <img src="{{ asset('Hakesa_logo.webp') }}" alt="Hakesa" width="64" height="60" class="w-16 h-16 mx-auto mb-4 object-contain">
                <h1 class="text-2xl font-bold text-gray-900">Crear Cuenta</h1>
                <p class="text-gray-500 mt-1">Únete a la familia Hakesa</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink focus:border-transparent transition-all"
                        placeholder="Tu nombre">
                    @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink focus:border-transparent transition-all"
                        placeholder="tu@email.com">
                    @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                        inputmode="numeric"
                        pattern="[+0-9\s\-]{7,20}"
                        maxlength="20"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink focus:border-transparent transition-all"
                        placeholder="+506 8888 9999"
                        oninput="this.value = this.value.replace(/[^+0-9\s\-]/g, '')">
                    @error('phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="birthday" class="block text-sm font-medium text-gray-700 mb-1">Fecha de nacimiento</label>
                    <input type="date" name="birthday" id="birthday" value="{{ old('birthday') }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink focus:border-transparent transition-all">
                    @error('birthday')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink focus:border-transparent transition-all"
                        placeholder="Mínimo 8 caracteres">
                    @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-hakesa-pink focus:border-transparent transition-all"
                        placeholder="Repite tu contraseña">
                </div>

                <button type="submit" class="w-full btn-hakesa py-3">Crear Cuenta</button>
            </form>

            <p class="mt-6 text-center text-gray-500 text-sm">
                ¿Ya tenés cuenta? <a href="{{ route('login') }}" class="text-hakesa-pink hover:text-hakesa-pink-dark font-medium">Iniciar sesión</a>
            </p>
        </div>
    </div>
</section>
@endsection
