<?php

use App\Models\AdminUser;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

describe('Admin Products CRUD', function () {
    beforeEach(function () {
        $this->admin = AdminUser::factory()->create();
        Storage::fake('public');
    });

    it('can list products', function () {
        Product::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')->get('/admin/products');
        $response->assertStatus(200);
        $response->assertSee('Gestión de Productos');
    });

    it('can display create product form', function () {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/products/create');
        $response->assertStatus(200);
        $response->assertSee('Crear Nuevo Producto');
    });

    it('can create a product', function () {
        $response = $this->actingAs($this->admin, 'admin')->post('/admin/products', [
            'name' => 'Taza Personalizada',
            'description' => 'Taza de cerámica con diseño personalizado',
            'price' => 5000,
            'category' => 'sublimacion',
            'service_type' => 'sublimacion',
            'stock' => 10,
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'name' => 'Taza Personalizada',
            'price' => 5000,
        ]);
    });

    it('can create a product with image', function () {
        $image = UploadedFile::fake()->image('product.jpg');

        $response = $this->actingAs($this->admin, 'admin')->post('/admin/products', [
            'name' => 'Taza con Imagen',
            'price' => 6000,
            'stock' => 5,
            'is_active' => true,
            'image' => $image,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['name' => 'Taza con Imagen']);
        Storage::disk('public')->assertExists('products/'.$image->hashName());
    });

    it('validates required fields when creating product', function () {
        $response = $this->actingAs($this->admin, 'admin')->post('/admin/products', []);

        $response->assertSessionHasErrors(['name', 'price', 'stock']);
    });

    it('can show a product', function () {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->get("/admin/products/{$product->id}");
        $response->assertStatus(200);
        $response->assertSee($product->name);
    });

    it('can display edit product form', function () {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->get("/admin/products/{$product->id}/edit");
        $response->assertStatus(200);
        $response->assertSee($product->name);
    });

    it('can update a product', function () {
        $product = Product::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->admin, 'admin')->put("/admin/products/{$product->id}", [
            'name' => 'Updated Name',
            'price' => 7500,
            'stock' => 15,
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Name',
            'price' => 7500,
        ]);
    });

    it('can delete a product', function () {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->delete("/admin/products/{$product->id}");

        $response->assertRedirect('/admin/products');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    });

    it('guest cannot access products', function () {
        $response = $this->get('/admin/products');
        $response->assertRedirect('/admin/login');
    });
});
