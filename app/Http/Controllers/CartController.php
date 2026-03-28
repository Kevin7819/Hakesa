<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = Cart::getOrCreateForUser(Auth::user());
        $cart->load('items.product');

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'customization' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $product->is_active) {
            return back()->with('error', 'Este producto no está disponible.');
        }

        $cart = Cart::getOrCreateForUser(Auth::user());

        $existingItem = $cart->items()
            ->where('product_id', $product->id);

        if ($request->filled('customization')) {
            $existingItem->where('customization', $request->input('customization'));
        } else {
            $existingItem->whereNull('customization');
        }

        $existing = $existingItem->first();

        if ($existing) {
            $existing->increment('quantity', $request->input('quantity', 1));
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->input('quantity', 1),
                'customization' => $request->input('customization'),
            ]);
        }

        return back()->with('success', "{$product->name} agregado al carrito.");
    }

    public function update(Request $request, CartItem $item): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $item->load('cart');

        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $item->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cantidad actualizada.');
    }

    public function remove(CartItem $item): RedirectResponse
    {
        $item->load('cart');

        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function clear(): RedirectResponse
    {
        $cart = Auth::user()->cart;

        if ($cart) {
            $cart->items()->delete();
        }

        return back()->with('success', 'Carrito vaciado.');
    }
}
