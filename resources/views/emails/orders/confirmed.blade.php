<x-mail::message>
# ¡Gracias por tu pedido, {{ $order->customer_name }}! 🎉

Tu pedido **{{ $order->order_number }}** ha sido recibido y está siendo procesado.

## Resumen del Pedido

@foreach ($items as $item)
- **{{ $item->product_name }}** × {{ $item->quantity }} — ₡{{ number_format($item->subtotal, 0, ',', '.') }}
@if ($item->customization)
  _Personalización: {{ $item->customization }}_
@endif
@endforeach

---

**Subtotal:** ₡{{ number_format($order->subtotal, 0, ',', '.') }}
@if ($order->shipping_cost > 0)
**Envío:** ₡{{ number_format($order->shipping_cost, 0, ',', '.') }}
@endif
**Total:** ₡{{ number_format($order->total, 0, ',', '.') }}

@if ($order->notes)
## Notas del Pedido

{{ $order->notes }}
@endif

<x-mail::button :url="route('orders.show', $order)" color="primary">
Ver mi pedido
</x-mail::button>

Te notificaremos cuando tu pedido sea enviado. Si tienes alguna duda, contáctanos por WhatsApp.

Saludos,<br>
**El equipo de Gracia Creativa**
</x-mail::message>
