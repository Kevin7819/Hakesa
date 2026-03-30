<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Cart', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create(['stock' => 10, 'is_active' => true]);
    });

    it('guest cannot access cart', function () {
        $response = $this->get('/carrito');
        $response->assertRedirect('/login');
    });

    it('authenticated user can view cart', function () {
        $response = $this->actingAs($this->user)->get('/carrito');
        $response->assertStatus(200);
        $response->assertSee('Mi Carrito');
    });

    it('can add a product to cart', function () {
        $response = $this->actingAs($this->user)
            ->postJson("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['cart_count' => 1]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);
    });

    it('adding same product increments quantity', function () {
        $this->actingAs($this->user)
            ->postJson("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $this->actingAs($this->user)
            ->postJson("/carrito/agregar/{$this->product->id}", ['quantity' => 2]);

        $response = $this->actingAs($this->user)
            ->postJson("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $response->assertJsonFragment(['cart_count' => 4]);
    });

    it('can add product with customization', function () {
        $response = $this->actingAs($this->user)
            ->postJson("/carrito/agregar/{$this->product->id}", [
                'quantity' => 1,
                'customization' => 'Quiero mi nombre grabado',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'customization' => 'Quiero mi nombre grabado',
        ]);
    });

    it('can update cart item quantity', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $cartItem = $this->user->cart->items->first();

        $response = $this->actingAs($this->user)
            ->patchJson("/carrito/{$cartItem->id}", ['quantity' => 5]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['cart_count' => 5]);
    });

    it('can remove item from cart', function () {
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $cartItem = $this->user->cart->items->first();

        $response = $this->actingAs($this->user)
            ->deleteJson("/carrito/{$cartItem->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['cart_count' => 0]);
    });

    it('can clear entire cart', function () {
        Product::factory()->create(['stock' => 10]);
        $this->actingAs($this->user)
            ->post("/carrito/agregar/{$this->product->id}", ['quantity' => 1]);

        $response = $this->actingAs($this->user)->deleteJson('/carrito');
        $response->assertStatus(200);
        $response->assertJsonFragment(['cart_count' => 0]);
    });

    it('cannot add inactive product to cart', function () {
        $inactiveProduct = Product::factory()->inactive()->create(['stock' => 10]);

        $response = $this->actingAs($this->user)
            ->postJson("/carrito/agregar/{$inactiveProduct->id}", ['quantity' => 1]);

        $response->assertStatus(422);
    });
});
