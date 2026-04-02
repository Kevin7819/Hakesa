<x-mail::message>
# ¡Gracias por tu pedido, {{ $order->customer_name }}!

Tu pedido **{{ $order->order_number }}** ha sido recibido y está siendo procesado.

## Resumen del Pedido

@foreach ($items as $item)
- **{{ $item->product->name }}** × {{ $item->quantity }} — ₡{{ number_format($item->subtotal, 2) }}
@if ($item->customization)
  _Personalización: {{ $item->customization }}_
@endif
@endforeach

---

**Subtotal:** ₡{{ number_format($order->subtotal, 2) }}
@if ($order->shipping_cost > 0)
**Envío:** ₡{{ number_format($order->shipping_cost, 2) }}
@endif
**Total:** ₡{{ number_format($order->total, 2) }}

@if ($order->notes)
## Notas del Pedido

{{ $order->notes }}
@endif

<x-mail::button :url="route('orders.show', $order)">
Ver mi pedido
</x-mail::button>

Te notificaremos cuando tu pedido sea enviado.

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
