<?php

use App\Models\AdminUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Admin Authentication', function () {
    it('can display the admin login page', function () {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Hakesa Admin');
    });

    it('admin can login with valid credentials', function () {
        $admin = AdminUser::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'super-admin',
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin, 'admin');
    });

    it('admin cannot login with invalid credentials', function () {
        AdminUser::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    });

    it('admin can logout', function () {
        $admin = AdminUser::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post('/admin/logout');
        $response->assertRedirect('/admin/login');
        $this->assertGuest('admin');
    });

    it('guest cannot access admin dashboard', function () {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    });

    it('authenticated admin can access dashboard', function () {
        $admin = AdminUser::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/dashboard');
        $response->assertStatus(200);
    });
});
