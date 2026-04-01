<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Checkout', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create(['stock' => 10, 'price' => 5000]);
    });

    it('guest cannot access checkout', function () {
        $response = $this->get('/checkout');
        $response->assertRedirect('/login');
    });

    it('redirects to cart when cart is empty', function () {
        $response = $this->actingAs($this->user)->get('/checkout');
        $response->assertRedirect('/carrito');
    });

    it('can display checkout page with items in cart', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 2]);

        $response = $this->actingAs($this->user)->get('/checkout');
        $response->assertStatus(200);
        $response->assertSee('Finalizar Pedido');
        $response->assertSee($this->product->name);
    });

    it('checkout shows user data as read-only', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $response = $this->actingAs($this->user)->get('/checkout');
        $response->assertSee($this->user->name);
        $response->assertSee($this->user->email);
    });

    it('can complete checkout and create order', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 2]);

        $response = $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'customer_phone' => '+506 8888 9999',
                'notes' => 'Entregar por la tarde',
            ]);

        $order = Order::where('user_id', $this->user->id)->first();
        expect($order)->not->toBeNull();
        expect($order->customer_name)->toBe($this->user->name);
        expect($order->status)->toBe('pending');
        expect($order->items)->toHaveCount(1);

        $response->assertRedirect("/mis-pedidos/{$order->id}");
    });

    it('order number follows HAK- format', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'customer_phone' => '+506 8888 9999',
            ]);

        $order = Order::where('user_id', $this->user->id)->first();
        expect($order->order_number)->toStartWith('HAK-');
    });

    it('checkout decrements product stock', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 3]);

        $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'customer_phone' => '+506 8888 9999',
            ]);

        $this->product->refresh();
        expect($this->product->stock)->toBe(7);
    });

    it('checkout clears cart after order', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'customer_phone' => '+506 8888 9999',
            ]);

        $cart = $this->user->cart;
        expect($cart->items->count())->toBe(0);
    });

    it('checkout saves customization on order items', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", [
                'quantity' => 1,
                'customization' => 'Grabar nombre: Kevin',
            ]);

        $cartItem = $this->user->cart->items->first();

        $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'customer_phone' => '+506 8888 9999',
                'customizations' => [$cartItem->id => 'Grabar nombre actualizado'],
            ]);

        $order = Order::where('user_id', $this->user->id)->first();
        expect($order->items->first()->customization)->toBe('Grabar nombre actualizado');
    });

    it('rejects checkout with insufficient stock', function () {
        $lowStock = Product::factory()->create(['stock' => 1, 'price' => 3000]);

        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$lowStock->id}", ['quantity' => 5]);

        $response = $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'customer_phone' => '+506 8888 9999',
            ]);

        $response->assertSessionHas('error');
    });

    it('checkout requires customer_name', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $response = $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => '',
                'customer_email' => $this->user->email,
                'customer_phone' => '+506 8888 9999',
            ]);

        $response->assertSessionHasErrors('customer_name');
    });

    it('checkout requires valid email', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $response = $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => 'no-es-email',
                'customer_phone' => '+506 8888 9999',
            ]);

        $response->assertSessionHasErrors('customer_email');
    });

    it('can view order after checkout', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'customer_phone' => '+506 8888 9999',
            ]);

        $order = Order::where('user_id', $this->user->id)->first();
        $response = $this->actingAs($this->user)->get("/mis-pedidos/{$order->id}");
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
    });

    it('user cannot view another users order', function () {
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $this->actingAs($otherUser)
            ->post('/checkout', [
                'customer_name' => $otherUser->name,
                'customer_email' => $otherUser->email,
                'customer_phone' => '+506 8888 9999',
            ]);

        $order = Order::where('user_id', $otherUser->id)->first();
        $response = $this->actingAs($this->user)->get("/mis-pedidos/{$order->id}");
        $response->assertStatus(403);
    });

    it('rejects checkout when product stock is zero', function () {
        $zeroStock = Product::factory()->create(['stock' => 0, 'price' => 3000]);

        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$zeroStock->id}", ['quantity' => 1]);

        $response = $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'customer_phone' => '+506 8888 9999',
            ]);

        $response->assertSessionHas('error');
    });

    it('displays error message on checkout failure', function () {
        $lowStock = Product::factory()->create(['stock' => 1, 'price' => 3000]);

        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$lowStock->id}", ['quantity' => 5]);

        $response = $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'customer_phone' => '+506 8888 9999',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    });

    it('checkout requires customer_phone validation', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $response = $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'customer_phone' => str_repeat('9', 21),
            ]);

        $response->assertSessionHasErrors('customer_phone');
    });

    it('checkout requires notes max length', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $response = $this->actingAs($this->user)
            ->post('/checkout', [
                'customer_name' => $this->user->name,
                'customer_email' => $this->user->email,
                'customer_phone' => '+506 8888 9999',
                'notes' => str_repeat('a', 1001),
            ]);

        $response->assertSessionHasErrors('notes');
    });
});
