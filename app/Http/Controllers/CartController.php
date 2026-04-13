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
        $customization = $request->input('customization');

        $result = DB::transaction(function () use ($cart, $product, $requestedQty, $customization) {
            // Re-fetch product with lock for update to ensure fresh stock data
            $lockedProduct = Product::lockForUpdate()->find($product->id);

            if ($requestedQty > $lockedProduct->stock) {
                return "Stock insuficiente. Disponible: {$lockedProduct->stock}";
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

                if ($newQty > $lockedProduct->stock) {
                    return "Stock insuficiente. Ya tenés {$existing->quantity} en el carrito, disponible: {$lockedProduct->stock}";
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
            return $request->wantsJson()
                ? response()->json(['message' => $result], 422)
                : back()->with('error', $result);
        }

        $cartCount = (int) $cart->items()->sum('quantity');

        $message = "¡{$product->name} agregado al carrito!";

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'cart_count' => $cartCount,
            ]);
        }

        return back()->with('success', $message);
    }

    public function update(Request $request, CartItem $item): JsonResponse|RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        // Ownership check via Eloquent relationship
        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $product = $item->product;

        if ($request->quantity > $product->stock) {
            $msg = "Stock insuficiente. Disponible: {$product->stock}";

            return $request->wantsJson()
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $item->update(['quantity' => $request->quantity]);

        if ($request->wantsJson()) {
            $cart = $item->cart;
            $cart->load('items.product');

            $cartTotal = $cart->total;
            $itemSubtotal = $product->price * $request->quantity;
            $cartCount = $cart->items->sum('quantity');

            return response()->json([
                'message' => 'Cantidad actualizada.',
                'cart_count' => $cartCount,
                'cart_total' => '₡'.number_format($cartTotal, 0, ',', '.'),
                'item_subtotal' => '₡'.number_format($itemSubtotal, 0, ',', '.'),
                'item_id' => $item->id,
            ]);
        }

        return back()->with('success', 'Cantidad actualizada.');
    }

    public function remove(Request $request, CartItem $item): JsonResponse|RedirectResponse
    {
        // Ownership check via Eloquent relationship
        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cartId = $item->cart_id;
        $item->delete();

        if ($request->wantsJson()) {
            $cartTotal = (float) CartItem::where('cart_id', $cartId)
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->sum(DB::raw('cart_items.quantity * products.price'));

            $cartCount = (int) CartItem::where('cart_id', $cartId)->sum('quantity');

            return response()->json([
                'message' => 'Producto eliminado del carrito.',
                'cart_count' => $cartCount,
                'cart_total' => '₡'.number_format($cartTotal, 0, ',', '.'),
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
