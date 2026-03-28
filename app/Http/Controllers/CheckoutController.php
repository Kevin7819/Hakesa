<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $cart = Cart::getOrCreateForUser(Auth::user());
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $user = Auth::user();

        return view('checkout.index', compact('cart', 'user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $cart = Cart::getOrCreateForUser(Auth::user());
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        // Validate stock before creating order
        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', "Stock insuficiente para {$item->product->name}. Disponible: {$item->product->stock}");
            }
        }

        $subtotal = $cart->total;

        $order = DB::transaction(function () use ($request, $cart, $subtotal) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'total' => $subtotal,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                    'customization' => $item->customization,
                ]);

                // Decrement stock
                $item->product->decrement('stock', $item->quantity);
            }

            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order)
            ->with('success', '¡Pedido realizado exitosamente! Número: '.$order->order_number);
    }
}
