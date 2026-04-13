<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CartItemResource;
use App\Http\Resources\Api\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartApiController extends Controller
{
    public function index(): JsonResponse
    {
        $cart = Cart::getOrCreateForUser(Auth::user());
        $cart->load('items.product');

        return response()->json([
            'data' => new CartResource($cart),
        ]);
    }

    public function add(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'customization' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $product->is_active) {
            return response()->json(['message' => 'Este producto no está disponible.'], 422);
        }

        $cart = Cart::getOrCreateForUser(Auth::user());
        $requestedQty = $request->input('quantity', 1);
        $customization = $request->input('customization');

        $result = DB::transaction(function () use ($cart, $product, $requestedQty, $customization) {
            $product->lockForUpdate()->refresh();

            if ($requestedQty > $product->stock) {
                return "Stock insuficiente. Disponible: {$product->stock}";
            }

            $existingQuery = $cart->items()
                ->where('product_id', $product->id);

            if ($customization !== null) {
                $existingQuery->where('customization', $customization);
            } else {
                $existingQuery->whereNull('customization');
            }

            $existing = $existingQuery->first();

            if ($existing) {
                $newQty = $existing->quantity + $requestedQty;

                if ($newQty > $product->stock) {
                    return "Stock insuficiente. Ya tenés {$existing->quantity} en el carrito, disponible: {$product->stock}";
                }

                $existing->update(['quantity' => $newQty]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $requestedQty,
                    'customization' => $customization,
                ]);
            }

            return null;
        });

        if ($result !== null) {
            return response()->json(['message' => $result], 422);
        }

        $cart->load('items.product');

        return response()->json([
            'message' => "¡{$product->name} agregado al carrito!",
            'data' => new CartResource($cart),
        ]);
    }

    public function update(Request $request, CartItem $item): JsonResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        if ($item->cart->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $product = $item->product;

        if ($request->quantity > $product->stock) {
            return response()->json(['message' => "Stock insuficiente. Disponible: {$product->stock}"], 422);
        }

        $item->update(['quantity' => $request->quantity]);
        $item->load('product');

        return response()->json([
            'message' => 'Cantidad actualizada.',
            'data' => new CartItemResource($item),
        ]);
    }

    public function remove(Request $request, CartItem $item): JsonResponse
    {
        if ($item->cart->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $item->delete();

        return response()->json([
            'message' => 'Producto eliminado del carrito.',
        ]);
    }

    public function clear(Request $request): JsonResponse
    {
        $cart = Auth::user()->cart;

        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json([
            'message' => 'Carrito vaciado.',
        ]);
    }
}
