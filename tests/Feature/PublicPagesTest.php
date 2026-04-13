<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Public Pages', function () {
    it('can display the welcome page', function () {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Gracia Creativa');
    });

    it('shows active products on welcome page', function () {
        $activeProduct = Product::factory()->create([
            'name' => 'Taza Activa',
            'is_active' => true,
        ]);
        Product::factory()->inactive()->create([
            'name' => 'Taza Inactiva',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Taza Activa');
        $response->assertDontSee('Taza Inactiva');
    });

    it('shows empty state when no products exist', function () {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Próximamente más productos');
    });
});
