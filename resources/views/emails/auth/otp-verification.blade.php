<x-mail::message>
# Restablecimiento de contraseña 🔐

Recibimos una solicitud para restablecer la contraseña de tu cuenta en **Gracia Creativa**.

Tu código de verificación es:

<x-mail::panel style="info">
# **{{ $otpCode }}**
</x-mail::panel>

Este código expira en **{{ $expiresInMinutes }} minutos**. Si no solicitaste este cambio, puedes ignorar este mensaje de forma segura.

<x-mail::button :url="route('password.reset.otp.verify')" color="primary">
Ingresar código
</x-mail::button>

Saludos,<br>
**El equipo de Gracia Creativa**
</x-mail::message>
