<?php

use App\Mail\OtpVerification;
use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

describe('OtpService', function () {
    it('generates and sends OTP for existing user', function () {
        Mail::fake();
        $user = User::factory()->create(['email' => 'test@test.com']);

        $service = app(OtpService::class);
        $result = $service->generateAndSend('test@test.com');

        expect($result)->toBeTrue();
        Mail::assertQueued(OtpVerification::class);
        $this->assertDatabaseHas('password_reset_otps', [
            'email' => 'test@test.com',
        ]);
    });

    it('returns true for non-existing email to prevent enumeration', function () {
        Mail::fake();

        $service = app(OtpService::class);
        $result = $service->generateAndSend('nobody@test.com');

        expect($result)->toBeTrue();
        Mail::assertNothingQueued();
    });

    it('verifies valid OTP', function () {
        $user = User::factory()->create(['email' => 'test@test.com']);
        $otp = PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        $service = app(OtpService::class);
        $result = $service->verify('test@test.com', '123456');

        expect($result)->not->toBeNull();
        expect($result->id)->toBe($otp->id);
    });

    it('rejects expired OTP', function () {
        $otp = PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->subMinutes(1),
        ]);

        $service = app(OtpService::class);
        $result = $service->verify('test@test.com', '123456');

        expect($result)->toBeNull();
    });

    it('rejects invalid OTP code', function () {
        $otp = PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        $service = app(OtpService::class);
        $result = $service->verify('test@test.com', '999999');

        expect($result)->toBeNull();
    });

    it('marks OTP as used', function () {
        $otp = PasswordResetOtp::create([
            'email' => 'test@test.com',
            'otp_code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(10),
        ]);

        $service = app(OtpService::class);
        $service->markAsUsed($otp);

        $otp->refresh();
        expect($otp->used_at)->not->toBeNull();
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
        $otp = PasswordResetOtp::create([
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
        $otp = PasswordResetOtp::create([
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

        $otp = PasswordResetOtp::create([
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
});
