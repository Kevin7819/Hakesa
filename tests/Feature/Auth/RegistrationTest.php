<?php

describe('Registration', function () {
    it('registration screen can be rendered with form', function () {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Crear Cuenta');
        $response->assertSee('Nombre completo');
        $response->assertSee('_token');
    });

    it('new users can register', function () {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('catalog.index', absolute: false));
    });
});
