<x-mail::message>
# Restablecimiento de contraseña

Recibimos una solicitud para restablecer la contraseña de tu cuenta en **{{ config('app.name') }}**.

Tu código de verificación es:

<x-mail::panel>
# {{ $otpCode }}
</x-mail::panel>

Este código expira en **{{ $expiresInMinutes }} minutos**. Si no solicitaste este cambio, puedes ignorar este mensaje.

<x-mail::button :url="route('password.reset.otp.verify')">
Ingresar código
</x-mail::button>

Saludos,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>
