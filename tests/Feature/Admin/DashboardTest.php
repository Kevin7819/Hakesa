<?php

use App\Models\AdminUser;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Admin Dashboard', function () {
    beforeEach(function () {
        $this->admin = AdminUser::factory()->create();
    });

    it('can display dashboard with stats', function () {
        Product::factory()->count(3)->create();
        Order::factory()->pending()->count(2)->create();
        User::factory()->count(5)->create();

        $response = $this->actingAs($this->admin, 'admin')->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    });

    it('shows recent orders on dashboard', function () {
        $order = Order::factory()->create([
            'customer_name' => 'Juan Pérez',
            'total' => 15000,
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Juan Pérez');
    });

    it('shows zero stats when no data', function () {
        $response = $this->actingAs($this->admin, 'admin')->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('0');
    });

    it('guest cannot access dashboard', function () {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    });
});
