<?php

use App\Models\User;

describe('Profile', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('profile page is displayed', function () {
        $response = $this
            ->actingAs($this->user)
            ->get('/perfil');

        $response->assertOk();
    });

    it('profile information can be updated', function () {
        $response = $this
            ->actingAs($this->user)
            ->patch('/perfil', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/perfil');

        $this->user->refresh();

        $this->assertSame('Test User', $this->user->name);
        $this->assertSame('test@example.com', $this->user->email);
        $this->assertNull($this->user->email_verified_at);
    });

    it('email verification status is unchanged when the email address is unchanged', function () {
        $response = $this
            ->actingAs($this->user)
            ->patch('/perfil', [
                'name' => 'Test User',
                'email' => $this->user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/perfil');

        $this->assertNotNull($this->user->refresh()->email_verified_at);
    });

    it('user can delete their account', function () {
        $response = $this
            ->actingAs($this->user)
            ->delete('/perfil', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($this->user->fresh());
    });

    it('correct password must be provided to delete account', function () {
        $response = $this
            ->actingAs($this->user)
            ->from('/perfil')
            ->delete('/perfil', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/perfil');

        $this->assertNotNull($this->user->fresh());
    });
});
