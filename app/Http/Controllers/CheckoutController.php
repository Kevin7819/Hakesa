<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
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

        // Update customizations on cart items before creating order
        $customizations = $request->input('customizations', []);
        foreach ($customizations as $itemId => $text) {
            $cartItem = $cart->items->firstWhere('id', $itemId);
            if ($cartItem) {
                $cartItem->update(['customization' => $text ?: null]);
            }
        }

        // Reload items with updated customizations
        $cart->load('items.product');

        $subtotal = $cart->total;

        try {
            $order = DB::transaction(function () use ($request, $cart, $subtotal) {
                $user = Auth::user();

                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $user->id,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone ?? $request->customer_phone,
                    'customer_address' => null,
                    'subtotal' => $subtotal,
                    'shipping_cost' => 0,
                    'total' => $subtotal,
                    'status' => 'pending',
                    'notes' => $request->notes,
                ]);

                foreach ($cart->items as $item) {
                    // Lock the row to prevent race conditions on stock
                    $product = Product::lockForUpdate()->find($item->product_id);

                    // Check if product still exists
                    if (! $product) {
                        throw new \Exception("El producto '{$item->product->name}' ya no est\u00e1 disponible.");
                    }

                    if ($product->stock < $item->quantity) {
                        throw new \Exception("Stock insuficiente para {$product->name}. Disponible: {$product->stock}");
                    }

                    $itemSubtotal = $product->price * $item->quantity;

                    // Create order item with denormalized data
                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $itemSubtotal,
                        'customization' => $item->customization,
                    ]);

                    // Decrement stock atomically
                    $product->decrement('stock', $item->quantity);
                }

                $cart->items()->delete();

                return $order;
            });

            return redirect()->route('orders.show', $order)
                ->with('success', "\u{1f389} \u00a1Pedido realizado exitosamente! N\u00famero: {$order->order_number}\n\nNos estaremos contactando v\u00eda WhatsApp al n\u00famero registrado para coordinar tu pedido y personalizaci\u00f3n.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
