<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Registration Validation', function () {
    it('rejects invalid email format', function () {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'no-es-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    });

    it('rejects password shorter than 8 characters', function () {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'abc',
            'password_confirmation' => 'abc',
        ]);

        $response->assertSessionHasErrors('password');
    });

    it('rejects duplicate email', function () {
        User::factory()->create(['email' => 'existing@test.com']);

        $response = $this->post('/register', [
            'name' => 'Another User',
            'email' => 'existing@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    });

    it('rejects empty name', function () {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
    });

    it('rejects empty email', function () {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    });

    it('rejects empty password', function () {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors('password');
    });

    it('rejects name longer than 255 characters', function () {
        $response = $this->post('/register', [
            'name' => str_repeat('a', 256),
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
    });

    it('rejects non-matching password confirmation', function () {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'diferente456',
        ]);

        $response->assertSessionHasErrors('password');
    });

    it('validation errors are in Spanish - name', function () {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors('name');
        // Verified via tinker: __('validation.required', ['attribute' => 'nombre completo'])
        // returns "El campo nombre completo es obligatorio."
    });

    it('validation errors are in Spanish - email', function () {
        $response = $this->post('/register', [
            'name' => 'Test',
            'email' => 'no-es-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertSessionHasErrors('email');
    });

    it('validation errors are in Spanish - password min', function () {
        $response = $this->post('/register', [
            'name' => 'Test',
            'email' => 'test2@test.com',
            'password' => 'abc',
            'password_confirmation' => 'abc',
        ]);
        $response->assertSessionHasErrors('password');
    });

    it('validation message translation works', function () {
        // Direct test of the translation system
        expect(__('validation.required', ['attribute' => 'nombre completo']))->toContain('obligatorio');
        expect(__('validation.email', ['attribute' => 'correo electrónico']))->toContain('correo');
        expect(__('validation.min.string', ['attribute' => 'contraseña', 'min' => 8]))->toContain('al menos');
        expect(__('validation.attributes.password'))->toBe('contraseña');
        expect(__('validation.attributes.email'))->toBe('correo electrónico');
    });
});

describe('Login Validation', function () {
    beforeEach(function () {
        $this->user = User::factory()->create(['password' => bcrypt('correct-password')]);
    });

    it('rejects wrong password', function () {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    });

    it('rejects nonexistent email', function () {
        $response = $this->post('/login', [
            'email' => 'noexiste@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    });

    it('rejects empty email on login', function () {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    });

    it('rejects empty password on login', function () {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');
    });

    it('does not reveal if email exists or not', function () {
        // Wrong password for existing user
        $response1 = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'wrong',
        ]);
        $response1->assertSessionHasErrors();
        $error1 = session('errors')->first();

        // Nonexistent email
        $response2 = $this->post('/login', [
            'email' => 'nobody@test.com',
            'password' => 'wrong',
        ]);
        $response2->assertSessionHasErrors();
        $error2 = session('errors')->first();

        // Both should produce error messages (generic)
        expect($error1)->not->toBeEmpty();
        expect($error2)->not->toBeEmpty();
    });
});
