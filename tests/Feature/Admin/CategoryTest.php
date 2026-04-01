<?php

use App\Models\AdminUser;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Admin Categories CRUD', function () {
    beforeEach(function () {
        $this->admin = AdminUser::factory()->create();
    });

    it('can list categories', function () {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'admin')->get('/admin/categories');
        $response->assertStatus(200);
    });

    it('can display create category form', function () {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/categories/create');
        $response->assertStatus(200);
    });

    it('can create a category', function () {
        $response = $this->actingAs($this->admin, 'admin')->post('/admin/categories', [
            'name' => 'Nueva Categoría',
            'description' => 'Descripción de prueba',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', [
            'name' => 'Nueva Categoría',
            'slug' => 'nueva-categoria',
            'is_active' => true,
        ]);
    });

    it('auto-generates slug from name', function () {
        $this->actingAs($this->admin, 'admin')->post('/admin/categories', [
            'name' => 'Corte Láser Especial',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Corte Láser Especial',
            'slug' => 'corte-laser-especial',
        ]);
    });

    it('validates required name when creating category', function () {
        $response = $this->actingAs($this->admin, 'admin')->post('/admin/categories', []);

        $response->assertSessionHasErrors('name');
    });

    it('validates unique name when creating category', function () {
        Category::factory()->create(['name' => 'Sublimación']);

        $response = $this->actingAs($this->admin, 'admin')->post('/admin/categories', [
            'name' => 'Sublimación',
        ]);

        $response->assertSessionHasErrors('name');
    });

    it('can display edit category form', function () {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->get("/admin/categories/{$category->id}/edit");
        $response->assertStatus(200);
        $response->assertSee($category->name);
    });

    it('can update a category', function () {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->admin, 'admin')->put("/admin/categories/{$category->id}", [
            'name' => 'Updated Name',
            'description' => 'Nueva descripción',
            'is_active' => true,
            'sort_order' => 5,
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Name',
        ]);
    });

    it('can delete an empty category', function () {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin, 'admin')->delete("/admin/categories/{$category->id}");

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    });

    it('cannot delete category with products', function () {
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($this->admin, 'admin')->delete("/admin/categories/{$category->id}");

        $response->assertRedirect('/admin/categories');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    });

    it('guest cannot access categories', function () {
        $response = $this->get('/admin/categories');
        $response->assertRedirect('/admin/login');
    });
});
