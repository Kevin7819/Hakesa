<?php

use App\Models\AdminUser;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Admin Orders', function () {
    beforeEach(function () {
        $this->admin = AdminUser::factory()->create();
    });

    it('can list orders', function () {
        Order::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')->get('/admin/orders');
        $response->assertStatus(200);
        $response->assertSee('Gestión de Pedidos');
    });

    it('can show an order with items', function () {
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 5000,
            'quantity' => 2,
            'subtotal' => 10000,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get("/admin/orders/{$order->id}");
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee($product->name);
    });

    it('can update order status', function () {
        $order = Order::factory()->pending()->create();

        $response = $this->actingAs($this->admin, 'admin')->patch("/admin/orders/{$order->id}/status", [
            'status' => 'confirmed',
        ]);

        $response->assertRedirect('/admin/orders');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'confirmed',
        ]);
    });

    it('validates order status values', function () {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->patch("/admin/orders/{$order->id}/status", [
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors('status');
    });

    it('guest cannot access orders', function () {
        $response = $this->get('/admin/orders');
        $response->assertRedirect('/admin/login');
    });
});
