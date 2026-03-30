<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Catalog', function () {
    beforeEach(function () {
        $this->category = Category::factory()->create(['name' => 'Sublimación']);
        $this->products = Product::factory()->count(5)->create([
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);
    });

    it('can display catalog page', function () {
        $response = $this->get('/productos');
        $response->assertStatus(200);
        $response->assertSee('Nuestros Productos');
    });

    it('shows active products', function () {
        $response = $this->get('/productos');
        foreach ($this->products as $product) {
            $response->assertSee($product->name);
        }
    });

    it('does not show inactive products', function () {
        $inactive = Product::factory()->inactive()->create(['name' => 'Producto Inactivo']);

        $response = $this->get('/productos');
        $response->assertDontSee('Producto Inactivo');
    });

    it('can filter products by category', function () {
        $otherCategory = Category::factory()->create(['name' => 'Láser']);
        $otherProduct = Product::factory()->create([
            'category_id' => $otherCategory->id,
            'name' => 'Producto Laser',
            'is_active' => true,
        ]);

        $response = $this->get("/productos?category={$this->category->id}");
        $response->assertSee($this->products->first()->name);
        $response->assertDontSee('Producto Laser');
    });

    it('can search products by name', function () {
        $specific = Product::factory()->create([
            'name' => 'Taza Especial Unica',
            'is_active' => true,
        ]);

        $response = $this->get('/productos?search=Especial');
        $response->assertSee('Taza Especial Unica');
    });

    it('can filter by price range', function () {
        Product::factory()->create(['price' => 1000, 'name' => 'Barato', 'is_active' => true]);
        Product::factory()->create(['price' => 99999, 'name' => 'Carisimo', 'is_active' => true]);

        $response = $this->get('/productos?price_min=5000&price_max=20000');
        $response->assertDontSee('Barato');
        $response->assertDontSee('Carisimo');
    });

    it('can sort products by price ascending', function () {
        Product::factory()->create(['price' => 1000, 'name' => 'Mas Barato', 'is_active' => true]);
        Product::factory()->create(['price' => 99999, 'name' => 'Mas Caro', 'is_active' => true]);

        $response = $this->get('/productos?sort=price_asc');
        $response->assertStatus(200);
    });

    it('can show product detail page', function () {
        $product = $this->products->first();
        $response = $this->get("/productos/{$product->id}");
        $response->assertStatus(200);
        $response->assertSee($product->name);
    });

    it('returns 404 for inactive product detail', function () {
        $inactive = Product::factory()->inactive()->create();
        $response = $this->get("/productos/{$inactive->id}");
        $response->assertStatus(404);
    });

    it('shows related products on detail page', function () {
        $product = $this->products->first();
        $response = $this->get("/productos/{$product->id}");
        $response->assertStatus(200);
    });

    it('can filter via AJAX and return JSON', function () {
        $response = $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->get('/productos');

        $response->assertStatus(200);
        $response->assertJsonStructure(['html', 'pagination', 'results_info']);
    });

    it('AJAX filter returns correct products', function () {
        Product::factory()->create(['name' => 'Filtro AJAX Test', 'is_active' => true]);

        $response = $this->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->get('/productos?search=AJAX');

        $response->assertStatus(200);
        $data = $response->json();
        expect($data['html'])->toContain('Filtro AJAX Test');
        expect($data['results_info'])->toContain('1 resultado');
    });
});
