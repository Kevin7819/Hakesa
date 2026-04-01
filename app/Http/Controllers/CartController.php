<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = Cart::getOrCreateForUser(Auth::user());
        $cart->load('items.product');

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'customization' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $product->is_active) {
            return $request->wantsJson()
                ? response()->json(['message' => 'Este producto no está disponible.'], 422)
                : back()->with('error', 'Este producto no está disponible.');
        }

        $cart = Cart::getOrCreateForUser(Auth::user());

        $requestedQty = $request->input('quantity', 1);

        // Lock product row to prevent race conditions on stock
        $result = DB::transaction(function () use ($cart, $product, $requestedQty, $request) {
            $lockedProduct = Product::lockForUpdate()->find($product->id);

            if ($requestedQty > $lockedProduct->stock) {
                return "Stock insuficiente. Disponible: {$lockedProduct->stock}";
            }

            $existingItem = $cart->items()->where('product_id', $product->id);

            $customization = $request->input('customization');

            $customization !== null
                ? $existingItem->where('customization', $customization)
                : $existingItem->whereNull('customization');

            $existing = $existingItem->first();

            if ($existing) {
                $newQty = $existing->quantity + $requestedQty;

                if ($newQty > $lockedProduct->stock) {
                    return "Stock insuficiente. Ya tenés {$existing->quantity} en el carrito, disponible: {$lockedProduct->stock}";
                }

                $existing->update(['quantity' => $newQty]);

                return null;
            }

            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $requestedQty,
                'customization' => $request->input('customization'),
            ]);

            return null;
        });

        if ($result !== null) {
            return $request->wantsJson()
                ? response()->json(['message' => $result], 422)
                : back()->with('error', $result);
        }

        $message = "¡{$product->name} agregado al carrito!";

        if ($request->wantsJson()) {
            $cart->load('items');

            return response()->json([
                'message' => $message,
                'cart_count' => $cart->item_count,
            ]);
        }

        return back()->with('success', $message);
    }

    public function update(Request $request, CartItem $item): JsonResponse|RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $item->load('cart');

        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $item->load('product');

        if ($request->quantity > $item->product->stock) {
            $msg = "Stock insuficiente. Disponible: {$item->product->stock}";

            return $request->wantsJson()
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $item->update(['quantity' => $request->quantity]);

        if ($request->wantsJson()) {
            $item->cart->load('items');
            $item->load('product');

            return response()->json([
                'message' => 'Cantidad actualizada.',
                'cart_count' => $item->cart->item_count,
                'cart_total' => '₡'.number_format($item->cart->total, 0, ',', '.'),
                'item_subtotal' => '₡'.number_format($item->subtotal, 0, ',', '.'),
                'item_id' => $item->id,
            ]);
        }

        return back()->with('success', 'Cantidad actualizada.');
    }

    public function remove(Request $request, CartItem $item): JsonResponse|RedirectResponse
    {
        $item->load('cart');

        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart = $item->cart;
        $item->delete();

        if ($request->wantsJson()) {
            $cart->load('items');

            return response()->json([
                'message' => 'Producto eliminado del carrito.',
                'cart_count' => $cart->item_count,
                'cart_total' => '₡'.number_format($cart->total, 0, ',', '.'),
            ]);
        }

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function clear(Request $request): JsonResponse|RedirectResponse
    {
        $cart = Auth::user()->cart;

        if ($cart) {
            $cart->items()->delete();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Carrito vaciado.',
                'cart_count' => 0,
            ]);
        }

        return back()->with('success', 'Carrito vaciado.');
    }
}
