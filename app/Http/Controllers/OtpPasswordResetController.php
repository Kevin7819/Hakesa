<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OtpPasswordResetController extends Controller
{
    public function __construct(
        protected OtpService $otpService,
    ) {}

    /**
     * Show form to request OTP (enter email).
     */
    public function showRequestForm(): View
    {
        return view('auth.forgot-password-otp');
    }

    /**
     * Generate and send OTP to user email.
     */
    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Rate limit: max 3 OTP requests per email per hour
        $throttleKey = 'otp-request:'.$request->email;
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Demasiados intentos. Intenta de nuevo en {$seconds} segundos.",
            ]);
        }

        RateLimiter::hit($throttleKey, 3600);

        $otpCode = $this->otpService->generateAndSend($request->email);

        // Store email in session for OTP verification step
        session(['otp_reset_email' => $request->email]);

        // In dev/testing, store OTP in session for easy access — never in production
        if ($otpCode !== null && app()->environment('local', 'testing')) {
            session(['otp_dev_code' => $otpCode]);
        } else {
            session()->forget('otp_dev_code');
        }

        return redirect()->route('password.reset.otp.verify')
            ->with('status', 'Si el correo existe, recibirás un código de 6 dígitos.');
    }

    /**
     * Show form to enter OTP code.
     */
    public function showOtpForm(): View
    {
        return view('auth.verify-otp');
    }

    /**
     * Verify OTP code.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $email = session('otp_reset_email');
        if (! $email) {
            return redirect()->route('password.request')
                ->withErrors(['otp_code' => 'Sesión expirada. Solicita un nuevo código.']);
        }

        // Rate limit: max 5 OTP verifications per email per 10 minutes
        $throttleKey = 'otp-verify:'.$email;
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'otp_code' => "Demasiados intentos. Intenta de nuevo en {$seconds} segundos.",
            ]);
        }

        // verify() now marks OTP as used atomically
        $otp = $this->otpService->verify($email, $request->otp_code);

        if (! $otp) {
            RateLimiter::hit($throttleKey, 600);

            throw ValidationException::withMessages([
                'otp_code' => 'Código inválido o expirado.',
            ]);
        }

        // Generate reset token and store in session
        $token = $this->otpService->generateResetToken();
        session([
            'otp_reset_verified' => true,
            'otp_reset_verified_at' => now(),
            'otp_reset_token' => $token,
            'otp_reset_email' => $email,
        ]);

        return redirect()->route('password.reset.new', ['token' => $token]);
    }

    /**
     * Show form to set new password.
     */
    public function showNewPasswordForm(Request $request): View|RedirectResponse
    {
        $token = $request->route('token');

        if (! session('otp_reset_verified') || session('otp_reset_token') !== $token) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'Token inválido o expirado.']);
        }

        // Enforce 10-minute window from OTP verification
        $verifiedAt = session('otp_reset_verified_at');
        if (! $verifiedAt || now()->diffInMinutes($verifiedAt) > 10) {
            $this->clearOtpSession();

            return redirect()->route('password.request')
                ->withErrors(['token' => 'La sesión expiró. Solicita un nuevo código.']);
        }

        return view('auth.reset-password-otp', ['token' => $token]);
    }

    /**
     * Set new password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Verify OTP was completed and token matches session
        if (! session('otp_reset_verified') || session('otp_reset_token') !== $request->token) {
            return redirect()->route('password.request')
                ->withErrors(['token' => 'Token inválido. Debes verificar tu código primero.']);
        }

        // Enforce 10-minute window from OTP verification
        $verifiedAt = session('otp_reset_verified_at');
        if (! $verifiedAt || now()->diffInMinutes($verifiedAt) > 10) {
            $this->clearOtpSession();

            return redirect()->route('password.request')
                ->withErrors(['token' => 'La sesión expiró. Solicita un nuevo código.']);
        }

        $email = session('otp_reset_email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->clearOtpSession();

            return redirect()->route('login')
                ->withErrors(['email' => 'No se encontró la cuenta.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Clear session
        $this->clearOtpSession();

        // Regenerate session ID to prevent session fixation
        $request->session()->regenerate();

        return redirect()->route('login')
            ->with('status', 'Contraseña restablecida exitosamente. Ya puedes iniciar sesión.');
    }

    /**
     * Resend OTP code.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $email = session('otp_reset_email');
        if (! $email) {
            return redirect()->route('password.request');
        }

        // Rate limit: same as sendOtp — max 3 per email per hour
        $throttleKey = 'otp-request:'.$email;
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Demasiados intentos. Intenta de nuevo en {$seconds} segundos.",
            ]);
        }

        RateLimiter::hit($throttleKey, 3600);

        $otpCode = $this->otpService->generateAndSend($email);

        // In dev/testing, store OTP in session — never in production
        if ($otpCode !== null && app()->environment('local', 'testing')) {
            session(['otp_dev_code' => $otpCode]);
        } else {
            session()->forget('otp_dev_code');
        }

        return back()->with('status', 'Nuevo código enviado.');
    }

    /**
     * Clear all OTP-related session keys.
     */
    private function clearOtpSession(): void
    {
        session()->forget(['otp_reset_verified', 'otp_reset_verified_at', 'otp_reset_token', 'otp_reset_email', 'otp_dev_code']);
    }
}
