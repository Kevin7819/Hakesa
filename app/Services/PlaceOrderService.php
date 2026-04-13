<?php

namespace App\Services;

use App\Mail\OrderConfirmation;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PlaceOrderService
{
    /**
     * Place an order from a user's cart.
     *
     * Handles: order creation, stock validation, inventory decrement,
     * cart clearing, and order confirmation email — all in a transaction.
     *
     * @param  Cart  $cart  The loaded cart with items
     * @param  mixed  $user  The authenticated user
     * @param  array  $data  Additional order data (customer_phone, notes)
     * @return Order The created order with items loaded
     *
     * @throws Exception If stock is insufficient or product unavailable
     */
    public function execute(Cart $cart, $user, array $data = []): Order
    {
        $subtotal = $cart->total;

        $order = DB::transaction(function () use ($cart, $user, $data, $subtotal) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $data['customer_phone'] ?? $user->phone,
                'customer_address' => null,
                'subtotal' => $subtotal,
                'shipping_cost' => 0,
                'total' => $subtotal,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
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

        // Send confirmation email outside transaction
        try {
            Mail::to($order->customer_email)->queue(new OrderConfirmation($order));
        } catch (Exception $e) {
            Log::error('Order confirmation email failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            // Don't fail the order — order was successful
        }

        return $order->load('items');
    }

    /**
     * Log a checkout failure for debugging/monitoring.
     */
    public function logFailure($userId, ?int $cartId, Exception $exception, string $context = 'Checkout'): void
    {
        Log::error("{$context} failed", [
            'user_id' => $userId,
            'cart_id' => $cartId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
