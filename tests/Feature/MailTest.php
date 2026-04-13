<?php

use App\Mail\OrderConfirmation;
use App\Mail\WelcomeEmail;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

describe('OrderConfirmation Mailable', function () {
    it('renders with order data', function () {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 5000]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'subtotal' => 5000,
            'total' => 5000,
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 5000,
            'quantity' => 1,
            'subtotal' => 5000,
        ]);

        $mailable = new OrderConfirmation($order);

        $mailable->assertHasSubject("Confirmación de pedido {$order->order_number}");
        $mailable->assertSeeInText($order->order_number);
        $mailable->assertSeeInText($user->name);
    });
});

describe('WelcomeEmail Mailable', function () {
    it('renders with user data', function () {
        $user = User::factory()->create(['name' => 'Juan Pérez']);

        $mailable = new WelcomeEmail($user);

        $mailable->assertSeeInText('Juan Pérez');
        $mailable->assertSeeInText('Bienvenido');
    });
});

describe('Email sending in checkout flow', function () {
    it('queues order confirmation email after checkout', function () {
        Mail::fake();

        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 5000, 'is_active' => true]);

        $this->actingAs($user)
            ->post("/carrito/agregar/{$product->id}", ['quantity' => 1]);

        $response = $this->actingAs($user)
            ->post('/checkout', [
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '+506 8888 9999',
            ]);

        // Verify checkout succeeded
        $response->assertRedirect();

        // Mailable implements ShouldQueue, so we assert queued
        Mail::assertQueued(OrderConfirmation::class);
    });

    it('creates order even if email queue fails', function () {
        Mail::fake();
        // Simulate queue failure — mail::queue will throw
        Mail::shouldReceive('queue')->andThrow(new Exception('Queue connection failed'));

        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 5000, 'is_active' => true]);

        $this->actingAs($user)
            ->post("/carrito/agregar/{$product->id}", ['quantity' => 1]);

        $response = $this->actingAs($user)
            ->post('/checkout', [
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '+506 8888 9999',
            ]);

        // Order should still be created despite email failure
        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
    });
});

describe('Email sending in registration flow', function () {
    it('queues welcome email after registration', function () {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Verify registration succeeded
        $response->assertRedirect();

        // Mailable implements ShouldQueue, so we assert queued
        Mail::assertQueued(WelcomeEmail::class);
    });
});
