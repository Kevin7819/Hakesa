<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Services\PlaceOrderService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected PlaceOrderService $placeOrderService,
    ) {}

    public function index(): RedirectResponse|View
    {
        $cart = Cart::getOrCreateForUser(Auth::user());
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $user = Auth::user();

        return view('checkout.index', compact('cart', 'user'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $cart = Cart::getOrCreateForUser(Auth::user());
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $customizations = $request->input('customizations', []);
        foreach ($customizations as $itemId => $text) {
            $cartItem = $cart->items->firstWhere('id', $itemId);
            if ($cartItem) {
                $cartItem->update(['customization' => $text ?: null]);
            }
        }

        $cart->load('items.product');

        try {
            $order = $this->placeOrderService->execute($cart, Auth::user(), [
                'customer_phone' => $request->customer_phone,
                'notes' => $request->notes,
            ]);

            return redirect()->route('orders.show', $order)
                ->with('success', "🎉 ¡Pedido realizado exitosamente! Número: {$order->order_number}\n\nNos estaremos contactando vía WhatsApp al número registrado para coordinar tu pedido y personalización.");
        } catch (Exception $exception) {
            $this->placeOrderService->logFailure(Auth::id(), $cart->id ?? null, $exception, 'Checkout');

            return back()->with('error', 'Hubo un problema al procesar tu pedido. Por favor intentá de nuevo en unos momentos.');
        }
    }
}
