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

        // Optimized: single transaction with minimal queries
        $result = DB::transaction(function () use ($cart, $product, $requestedQty, $customization) {
            $stock = (int) DB::table('products')
                ->where('id', $product->id)
                ->lockForUpdate()
                ->value('stock');

            if ($requestedQty > $stock) {
                return "Stock insuficiente. Disponible: {$stock}";
            }

            // Build query for existing item
            $existingQuery = DB::table('cart_items')
                ->where('cart_id', $cart->id)
                ->where('product_id', $product->id);

            if ($customization !== null) {
                $existingQuery->where('customization', $customization);
            } else {
                $existingQuery->whereNull('customization');
            }

            $existing = $existingQuery->first();

            if ($existing) {
                $newQty = $existing->quantity + $requestedQty;

                if ($newQty > $stock) {
                    return "Stock insuficiente. Ya tenés {$existing->quantity} en el carrito, disponible: {$stock}";
                }

                DB::table('cart_items')
                    ->where('id', $existing->id)
                    ->update(['quantity' => $newQty, 'updated_at' => now()]);
            } else {
                DB::table('cart_items')->insert([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $requestedQty,
                    'customization' => $customization,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return null;
        });

        if ($result !== null) {
            return $request->wantsJson()
                ? response()->json(['message' => $result], 422)
                : back()->with('error', $result);
        }

        // Fast count without loading full cart
        $cartCount = (int) DB::table('cart_items')->where('cart_id', $cart->id)->sum('quantity');

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

        // Fast ownership check without loading relationship
        $cartOwnerId = (int) DB::table('carts')
            ->where('id', $item->cart_id)
            ->value('user_id');

        if ($cartOwnerId !== Auth::id()) {
            abort(403);
        }

        $stock = (int) DB::table('products')->where('id', $item->product_id)->value('stock');

        if ($request->quantity > $stock) {
            $msg = "Stock insuficiente. Disponible: {$stock}";

            return $request->wantsJson()
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        DB::table('cart_items')
            ->where('id', $item->id)
            ->update(['quantity' => $request->quantity, 'updated_at' => now()]);

        if ($request->wantsJson()) {
            $cartTotal = (float) DB::table('cart_items')
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->where('cart_items.cart_id', $item->cart_id)
                ->sum(DB::raw('cart_items.quantity * products.price'));

            $itemSubtotal = (float) DB::table('products')
                ->where('id', $item->product_id)
                ->value('price') * $request->quantity;

            $cartCount = (int) DB::table('cart_items')->where('cart_id', $item->cart_id)->sum('quantity');

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
        // Fast ownership check
        $cartOwnerId = (int) DB::table('carts')
            ->where('id', $item->cart_id)
            ->value('user_id');

        if ($cartOwnerId !== Auth::id()) {
            abort(403);
        }

        $cartId = $item->cart_id;
        $item->delete();

        if ($request->wantsJson()) {
            $cartTotal = (float) DB::table('cart_items')
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->where('cart_items.cart_id', $cartId)
                ->sum(DB::raw('cart_items.quantity * products.price'));

            $cartCount = (int) DB::table('cart_items')->where('cart_id', $cartId)->sum('quantity');

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
