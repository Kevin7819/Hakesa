<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\Api\CartResource;
use App\Http\Resources\Api\OrderResource;
use App\Models\Cart;
use App\Services\PlaceOrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CheckoutApiController extends Controller
{
    public function __construct(
        protected PlaceOrderService $placeOrderService,
    ) {}

    /**
     * Get checkout summary (cart + totals).
     */
    public function summary(): JsonResponse
    {
        $cart = Cart::getOrCreateForUser(Auth::user());
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Tu carrito está vacío.',
            ], 422);
        }

        return response()->json([
            'data' => new CartResource($cart),
        ]);
    }

    /**
     * Place an order.
     */
    public function store(CheckoutRequest $request): JsonResponse
    {
        $cart = Cart::getOrCreateForUser(Auth::user());
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Tu carrito está vacío.',
            ], 422);
        }

        try {
            $order = $this->placeOrderService->execute($cart, Auth::user(), [
                'customer_phone' => $request->customer_phone,
                'notes' => $request->notes,
            ]);

            return response()->json([
                'message' => "¡Pedido realizado exitosamente! Número: {$order->order_number}",
                'data' => new OrderResource($order),
            ], 201);
        } catch (Exception $exception) {
            $this->placeOrderService->logFailure(Auth::id(), $cart->id ?? null, $exception, 'API Checkout');

            return response()->json([
                'message' => 'Hubo un problema al procesar tu pedido. Por favor intentá de nuevo en unos momentos.',
            ], 500);
        }
    }
}
