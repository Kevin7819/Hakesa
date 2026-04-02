<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

        $customizations = $request->input('customizations', []);
        foreach ($customizations as $itemId => $text) {
            $cartItem = $cart->items->firstWhere('id', $itemId);
            if ($cartItem) {
                $cartItem->update(['customization' => $text ?: null]);
            }
        }

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
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if (! $product) {
                        throw new Exception("El producto '{$item->product_id}' ya no está disponible.");
                    }

                    if ($product->stock < $item->quantity) {
                        throw new Exception("Stock insuficiente para {$product->name}. Disponible: {$product->stock}");
                    }

                    $itemSubtotal = $product->price * $item->quantity;

                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $itemSubtotal,
                        'customization' => $item->customization,
                    ]);

                    $product->decrement('stock', $item->quantity);
                }

                $cart->items()->delete();

                return $order;
            });

            // Send order confirmation email
            Mail::to($order->customer_email)->queue(new OrderConfirmation($order));

            return redirect()->route('orders.show', $order)
                ->with('success', "🎉 ¡Pedido realizado exitosamente! Número: {$order->order_number}\n\nNos estaremos contactando vía WhatsApp al número registrado para coordinar tu pedido y personalización.");
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
