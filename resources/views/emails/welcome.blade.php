<x-mail::message>
# ¡Bienvenido/a a {{ config('app.name') }}, {{ $user->name }}!

Gracias por registrarte en nuestra tienda. Estamos felices de tenerte con nosotros.

Ahora puedes:

- Explorar nuestro catálogo de productos personalizados
- Guardar tus favoritos para después
- Realizar pedidos con seguimiento en tiempo real
- Dejar reseñas de tus compras

<x-mail::button :url="route('catalog.index')">
Ver catálogo
</x-mail::button>

Si tienes alguna pregunta, no dudes en contactarnos.

Saludos,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>
