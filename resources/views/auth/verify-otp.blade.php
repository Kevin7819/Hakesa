<x-guest-layout>
    <div class="mb-4 text-sm text-gray-400">
        Ingresa el código de 6 dígitos que enviamos a tu correo electrónico.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.reset.otp.verify.post') }}">
        @csrf

        <!-- OTP Code -->
        <div>
            <x-input-label for="otp_code" :value="'Código de verificación'" />
            <x-text-input id="otp_code" class="block mt-1 w-full text-center text-2xl tracking-widest" type="text" name="otp_code" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required autofocus autocomplete="one-time-code" />
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <button type="button" onclick="document.getElementById('resend-form').submit()" class="text-sm text-gracia-primary hover:underline">
                Reenviar código
            </button>
            <x-primary-button>
                Verificar código
            </x-primary-button>
        </div>
    </form>

    <form id="resend-form" method="POST" action="{{ route('password.reset.otp.resend') }}" class="hidden">
        @csrf
    </form>
</x-guest-layout>
