<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

describe('Password Update', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('password can be updated', function () {
        $response = $this
            ->actingAs($this->user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check('new-password', $this->user->refresh()->password));
    });

    it('correct password must be provided to update password', function () {
        $response = $this
            ->actingAs($this->user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('updatePassword', 'current_password')
            ->assertRedirect('/profile');
    });
});
