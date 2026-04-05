<?php

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Wishlist', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create(['price' => 1000, 'stock' => 5]);
    });

    it('guest cannot access wishlist page', function () {
        $response = $this->get(route('wishlist.index'));
        $response->assertRedirect(route('login'));
    });

    it('authenticated user can view empty wishlist', function () {
        $response = $this->actingAs($this->user)->get(route('wishlist.index'));
        $response->assertStatus(200);
        $response->assertSee('No tienes favoritos aún');
    });

    it('can add a product to wishlist', function () {
        $response = $this->actingAs($this->user)
            ->postJson(route('wishlist.store', $this->product));

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Producto agregado a favoritos', 'in_wishlist' => true]);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    });

    it('cannot add the same product twice', function () {
        Wishlist::create(['user_id' => $this->user->id, 'product_id' => $this->product->id]);

        $response = $this->actingAs($this->user)
            ->postJson(route('wishlist.store', $this->product));

        $response->assertStatus(200)
            ->assertJsonFragment(['in_wishlist' => true]);

        $this->assertCount(1, $this->user->wishlists);
    });

    it('can remove a product from wishlist', function () {
        Wishlist::create(['user_id' => $this->user->id, 'product_id' => $this->product->id]);

        $response = $this->actingAs($this->user)
            ->deleteJson(route('wishlist.destroy', $this->product));

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Producto removido de favoritos', 'in_wishlist' => false]);

        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    });

    it('cannot add inactive product to wishlist', function () {
        $this->product->update(['is_active' => false]);

        $response = $this->actingAs($this->user)
            ->postJson(route('wishlist.store', $this->product));

        $response->assertStatus(400);
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    });

    it('wishlist page shows products', function () {
        Wishlist::create(['user_id' => $this->user->id, 'product_id' => $this->product->id]);

        $response = $this->actingAs($this->user)->get(route('wishlist.index'));
        $response->assertStatus(200);
        $response->assertSee($this->product->name);
        $response->assertSee(number_format($this->product->price, 0, ',', '.'));
    });

    it('user cannot see another user wishlist items', function () {
        $otherUser = User::factory()->create();
        Wishlist::create(['user_id' => $otherUser->id, 'product_id' => $this->product->id]);

        $response = $this->actingAs($this->user)->get(route('wishlist.index'));
        $response->assertStatus(200);
        $response->assertSee('No tienes favoritos aún');
    });

    it('wishlist count is returned in JSON response', function () {
        Wishlist::create(['user_id' => $this->user->id, 'product_id' => $this->product->id]);
        $product2 = Product::factory()->create();
        Wishlist::create(['user_id' => $this->user->id, 'product_id' => $product2->id]);

        $response = $this->actingAs($this->user)
            ->deleteJson(route('wishlist.destroy', $this->product));

        $response->assertJsonFragment(['count' => 1]);
    });

    it('inactive products are removed from wishlist view', function () {
        Wishlist::create(['user_id' => $this->user->id, 'product_id' => $this->product->id]);
        $this->product->update(['is_active' => false]);

        $response = $this->actingAs($this->user)->get(route('wishlist.index'));
        $response->assertStatus(200);
        $response->assertSee('No tienes favoritos aún');
    });
});
