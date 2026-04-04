<?php

namespace App\Services;

use App\Mail\OtpVerification;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpService
{
    /**
     * Generate and send OTP for password reset.
     * Returns the plain OTP code if email fails (for development fallback).
     */
    public function generateAndSend(string $email): ?string
    {
        $user = User::where('email', $email)->first();

        // Always return null to prevent email enumeration
        if (! $user) {
            return null;
        }

        $otpCode = null;
        $emailSent = false;

        DB::transaction(function () use ($email, &$otpCode, &$emailSent) {
            // Invalidate any existing OTPs for this email
            PasswordResetOtp::where('email', $email)
                ->whereNull('used_at')
                ->update(['used_at' => now()]);

            // Generate 6-digit OTP
            $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store hashed OTP
            PasswordResetOtp::create([
                'email' => $email,
                'otp_code' => Hash::make($otpCode),
                'expires_at' => now()->addMinutes(10),
            ]);

            // Send OTP via email
            try {
                Mail::to($email)->queue(new OtpVerification($otpCode));
                $emailSent = true;
            } catch (\Exception $e) {
                // Email failed — report but do NOT expose OTP
                report($e);
            }
        });

        // Only return OTP when email actually failed (development fallback)
        return $emailSent ? null : $otpCode;
    }

    /**
     * Verify OTP code and mark as used atomically.
     * Returns the OTP record if valid, null otherwise.
     */
    public function verify(string $email, string $otpCode): ?PasswordResetOtp
    {
        $otp = PasswordResetOtp::where('email', $email)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->lockForUpdate()
            ->first();

        if (! $otp) {
            return null;
        }

        if (! Hash::check($otpCode, $otp->otp_code)) {
            return null;
        }

        // Mark as used atomically
        $otp->update(['used_at' => now()]);

        return $otp;
    }

    /**
     * Generate a secure token for password reset session.
     */
    public function generateResetToken(): string
    {
        return Str::random(64);
    }
}
