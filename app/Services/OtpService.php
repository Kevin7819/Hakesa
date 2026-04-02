<?php

namespace App\Services;

use App\Mail\OtpVerification;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpService
{
    /**
     * Generate and send OTP for password reset.
     */
    public function generateAndSend(string $email): bool
    {
        $user = User::where('email', $email)->first();

        // Always return true to prevent email enumeration
        if (! $user) {
            return true;
        }

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
        Mail::to($email)->queue(new OtpVerification($otpCode));

        return true;
    }

    /**
     * Verify OTP code.
     */
    public function verify(string $email, string $otpCode): ?PasswordResetOtp
    {
        $records = PasswordResetOtp::where('email', $email)
            ->whereNull('used_at')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($records as $record) {
            if ($record->isExpired()) {
                continue;
            }

            if (Hash::check($otpCode, $record->otp_code)) {
                return $record;
            }
        }

        return null;
    }

    /**
     * Mark OTP as used.
     */
    public function markAsUsed(PasswordResetOtp $otp): void
    {
        $otp->update(['used_at' => now()]);
    }

    /**
     * Generate a secure token for password reset session.
     */
    public function generateResetToken(): string
    {
        return Str::random(64);
    }
}
