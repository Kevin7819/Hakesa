<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

describe('Email Verification', function () {
    beforeEach(function () {
        $this->user = User::factory()->unverified()->create();
    });

    it('email verification screen can be rendered', function () {
        $response = $this->actingAs($this->user)->get('/verify-email');

        $response->assertStatus(200);
    });

    it('email can be verified', function () {
        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $this->user->id, 'hash' => sha1($this->user->email)]
        );

        $response = $this->actingAs($this->user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        expect($this->user->fresh()->hasVerifiedEmail())->toBeTrue();
        $response->assertRedirect(route('catalog.index', absolute: false).'?verified=1');
    });

    it('email is not verified with invalid hash', function () {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $this->user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($this->user)->get($verificationUrl);

        expect($this->user->fresh()->hasVerifiedEmail())->toBeFalse();
    });
});
