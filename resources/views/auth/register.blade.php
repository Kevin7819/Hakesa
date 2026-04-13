@extends('layouts.public')

@section('title', 'Crear Cuenta - Gracia Creativa')

@section('content')
<section class="min-h-screen flex items-center justify-center py-20 bg-gray-800">
    <div class="w-full max-w-md px-4">
        <div class="bg-gray-800 rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <img src="{{ asset('Gracia_Creativa_Logo_withou_background.png') }}" alt="Gracia Creativa" width="200" height="auto" class="w-52 h-auto mx-auto mb-4 object-contain">
                <h1 class="text-2xl font-bold text-white">Crear Cuenta</h1>
                <p class="text-gray-400 mt-1">Únete a la familia Gracia Creativa</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nombre completo *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent transition-all"
                        placeholder="Tu nombre">
                    @error('name')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent transition-all"
                        placeholder="tu@email.com">
                    @error('email')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-1">Teléfono</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                        inputmode="numeric"
                        pattern="^\+?[0-9\s\-]{7,20}$"
                        maxlength="20"
                        class="w-full px-4 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent transition-all"
                        placeholder="+506 8888 9999"
                        oninput="sanitizePhone(this)">
                    @error('phone')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="birthday" class="block text-sm font-medium text-gray-300 mb-1">Fecha de nacimiento</label>
                    <input type="text" name="birthday" id="birthday" value="{{ old('birthday') }}"
                        class="w-full px-4 py-2.5 border border-gray-500 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent transition-all text-white placeholder-gray-500"
                        placeholder="MM/DD/YYYY"
                        onfocus="(this.type='date')" onblur="(this.type='text')">
                    @error('birthday')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Contraseña *</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent transition-all"
                        placeholder="Mínimo 8 caracteres">
                    @error('password')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirmar contraseña *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary focus:border-transparent transition-all"
                        placeholder="Repite tu contraseña">
                </div>

                <button type="submit" class="w-full btn-gracia py-3">Crear Cuenta</button>
            </form>

            <p class="mt-6 text-center text-gray-400 text-sm">
                ¿Ya tenés cuenta? <a href="{{ route('login') }}" class="text-gracia-primary hover:text-gracia-primary-dark font-medium">Iniciar sesión</a>
            </p>
        </div>
    </div>
</section>

<script>
function sanitizePhone(input) {
    let value = input.value;
    // Allow + only at position 0
    const firstChar = value.charAt(0);
    const rest = value.substring(1).replace(/[^0-9\s\-]/g, '');
    input.value = (firstChar === '+' ? '+' : '') + rest;
}
</script>
@endsection
