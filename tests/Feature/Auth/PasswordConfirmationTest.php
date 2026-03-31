<?php

use App\Models\User;

describe('Password Confirmation', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('confirm password screen can be rendered', function () {
        $response = $this->actingAs($this->user)->get('/confirm-password');

        $response->assertStatus(200);
    });

    it('password can be confirmed', function () {
        $response = $this->actingAs($this->user)->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    });

    it('password is not confirmed with invalid password', function () {
        $response = $this->actingAs($this->user)->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    });
});
