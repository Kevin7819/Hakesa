<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Client Orders', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create(['price' => 5000]);
    });

    it('guest cannot access orders', function () {
        $response = $this->get('/mis-pedidos');
        $response->assertRedirect('/login');
    });

    it('can list own orders', function () {
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'customer_phone' => '+506 8888 9999',
            'subtotal' => 5000,
            'shipping_cost' => 0,
            'total' => 5000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->get('/mis-pedidos');
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
    });

    it('can view own order details', function () {
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $this->user->id,
            'customer_name' => $this->user->name,
            'customer_email' => $this->user->email,
            'customer_phone' => '+506 8888 9999',
            'subtotal' => 10000,
            'shipping_cost' => 0,
            'total' => 10000,
            'status' => 'pending',
        ]);

        $order->items()->create([
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'price' => 5000,
            'quantity' => 2,
            'subtotal' => 10000,
        ]);

        $response = $this->actingAs($this->user)->get("/mis-pedidos/{$order->id}");
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee($this->product->name);
    });

    it('cannot view another users order', function () {
        $otherUser = User::factory()->create();

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'user_id' => $otherUser->id,
            'customer_name' => $otherUser->name,
            'customer_email' => $otherUser->email,
            'customer_phone' => '+506 8888 9999',
            'subtotal' => 5000,
            'shipping_cost' => 0,
            'total' => 5000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->get("/mis-pedidos/{$order->id}");
        $response->assertStatus(403);
    });

    it('shows empty state when user has no orders', function () {
        $response = $this->actingAs($this->user)->get('/mis-pedidos');
        $response->assertStatus(200);
    });
});
