<?php

use App\Mail\OtpVerification;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

describe('OtpService', function () {
    it('generates and sends OTP for existing user', function () {
        Mail::fake();
        $user = User::factory()->create(['email' => 'test@test.com']);

        $service = app(OtpService::class);
        $result = $service->generateAndSend('test@test.com');

        // Returns null on success (OTP is sent via email, not exposed)
        // Only returns the OTP code string when email delivery fails (dev fallback)
        expect($result)->toBeNull();
        Mail::assertQueued(OtpVerification::class);
        $this->assertDatabaseHas('password_reset_otps', [
            'email' => 'test@test.com',
        ]);
    });

    it('returns null for non-existing email to prevent enumeration', function () {
        Mail::fake();

        $service = app(OtpService::class);
        $result = $service->generateAndSend('nobody@test.com');

        expect($result)->toBeNull();
        Mail::assertNothingQueued();
    });

    it('verifies valid OTP and marks it as used atomically', function () {
        $user = User::factory()->create(['email' => 'test@test.com']);
        PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        $service = app(OtpService::class);
        $result = $service->verify('test@test.com', '123456');

        expect($result)->not->toBeNull();
        expect($result->used_at)->not->toBeNull();
    });

    it('rejects expired OTP', function () {
        PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->subMinutes(1),
        ]);

        $service = app(OtpService::class);
        $result = $service->verify('test@test.com', '123456');

        expect($result)->toBeNull();
    });

    it('rejects invalid OTP code', function () {
        PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        $service = app(OtpService::class);
        $result = $service->verify('test@test.com', '999999');

        expect($result)->toBeNull();
    });

    it('rejects already-used OTP', function () {
        $otp = PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
            'used_at' => now(),
        ]);

        $service = app(OtpService::class);
        $result = $service->verify('test@test.com', '123456');

        expect($result)->toBeNull();
    });

    it('invalidates previous OTPs when generating new one', function () {
        Mail::fake();
        $user = User::factory()->create(['email' => 'test@test.com']);

        $service = app(OtpService::class);
        $service->generateAndSend('test@test.com');
        $service->generateAndSend('test@test.com');

        // Only the latest OTP should be unused
        $unusedCount = PasswordResetOtp::where('email', 'test@test.com')
            ->whereNull('used_at')
            ->count();

        expect($unusedCount)->toBe(1);
    });

    it('cannot verify same OTP twice (atomic mark-as-used)', function () {
        PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        $service = app(OtpService::class);

        // First verification succeeds
        $result1 = $service->verify('test@test.com', '123456');
        expect($result1)->not->toBeNull();

        // Second verification with same code fails
        $result2 = $service->verify('test@test.com', '123456');
        expect($result2)->toBeNull();
    });
});

describe('OTP Password Reset Flow', function () {
    it('shows forgot password form', function () {
        $response = $this->get('/forgot-password');
        $response->assertSuccessful();
        $response->assertSee('correo electrónico');
    });

    it('sends OTP and redirects to verification page', function () {
        Mail::fake();
        $user = User::factory()->create(['email' => 'test@test.com']);

        $response = $this->post('/forgot-password', ['email' => 'test@test.com']);

        $response->assertRedirect('/verify-otp');
        Mail::assertQueued(OtpVerification::class);
    });

    it('shows OTP verification form', function () {
        session(['otp_reset_email' => 'test@test.com']);

        $response = $this->get('/verify-otp');
        $response->assertSuccessful();
        $response->assertSee('código');
    });

    it('verifies OTP and redirects to new password form', function () {
        $user = User::factory()->create(['email' => 'test@test.com']);
        PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        session(['otp_reset_email' => 'test@test.com']);

        $response = $this->post('/verify-otp', ['otp_code' => '123456']);

        $response->assertRedirect();
        expect(session('otp_reset_verified'))->toBeTrue();
    });

    it('rejects invalid OTP code', function () {
        $user = User::factory()->create(['email' => 'test@test.com']);
        PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        session(['otp_reset_email' => 'test@test.com']);

        $response = $this->post('/verify-otp', ['otp_code' => '999999']);

        $response->assertSessionHasErrors('otp_code');
    });

    it('completes password reset with valid OTP', function () {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('old-password'),
        ]);

        PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        session([
            'otp_reset_email' => 'test@test.com',
            'otp_reset_verified' => true,
        ]);

        // Verify OTP
        $response = $this->post('/verify-otp', ['otp_code' => '123456']);
        $response->assertRedirect();

        $token = session('otp_reset_token');

        // Reset password
        $response = $this->post('/reset-password', [
            'token' => $token,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('status');

        // Verify new password works
        $user->refresh();
        expect(Hash::check('new-password-123', $user->password))->toBeTrue();
    });

    it('rejects password reset without OTP verification', function () {
        session(['otp_reset_verified' => false]);

        $response = $this->post('/reset-password', [
            'token' => 'fake-token',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHasErrors('token');
    });

    it('rejects password reset with mismatched token', function () {
        session([
            'otp_reset_verified' => true,
            'otp_reset_token' => 'real-token',
            'otp_reset_email' => 'test@test.com',
        ]);

        $response = $this->post('/reset-password', [
            'token' => 'wrong-token',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHasErrors('token');
    });

    it('redirects to request form when OTP verification has no email in session', function () {
        session()->forget('otp_reset_email');

        $response = $this->post('/verify-otp', ['otp_code' => '123456']);

        $response->assertRedirect('/forgot-password');
        $response->assertSessionHasErrors('otp_code');
    });

    it('rate limits OTP requests after 3 attempts per hour', function () {
        Mail::fake();
        $user = User::factory()->create(['email' => 'test@test.com']);
        RateLimiter::clear('otp-request:test@test.com');

        // 3 attempts
        for ($i = 0; $i < 3; $i++) {
            $this->post('/forgot-password', ['email' => 'test@test.com']);
        }

        // 4th attempt should be rate limited (route middleware returns 429)
        $response = $this->post('/forgot-password', ['email' => 'test@test.com']);
        $response->assertStatus(429);
    });

    it('rate limits OTP verification after 5 attempts per 10 minutes', function () {
        $user = User::factory()->create(['email' => 'test@test.com']);
        PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        session(['otp_reset_email' => 'test@test.com']);
        RateLimiter::clear('otp-verify:test@test.com');

        // 5 attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/verify-otp', ['otp_code' => 'wrong']);
        }

        // 6th attempt should be rate limited (route middleware returns 429)
        $response = $this->post('/verify-otp', ['otp_code' => '123456']);
        $response->assertStatus(429);
    });
});
