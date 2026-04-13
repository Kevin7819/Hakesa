<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    public function __construct(
        protected OtpService $otpService,
    ) {}

    /**
     * Login with email + password.
     * Returns a Sanctum token for mobile use.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        // Create token (mobile devices pass device_name like "iPhone - John's iPhone")
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'token' => $token,
        ]);
    }

    /**
     * Register a new user.
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\+]?[\d\s\-()]+$/'],
            'device_name' => ['required', 'string'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
        ]);

        $token = $user->createToken($data['device_name'])->plainTextToken;

        return response()->json([
            'message' => 'Cuenta creada exitosamente.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'token' => $token,
        ], 201);
    }

    /**
     * Request OTP for password reset.
     */
    public function requestOtp(Request $request): JsonResponse
    {
        $email = $request->validate(['email' => ['required', 'email']])['email'];

        $throttleKey = 'otp-request:'.$email;
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return response()->json([
                'message' => "Demasiados intentos. Intenta de nuevo en {$seconds} segundos.",
            ], 429);
        }

        RateLimiter::hit($throttleKey, 3600);

        $this->otpService->generateAndSend($email);

        return response()->json([
            'message' => 'Si el correo existe, recibirás un código de 6 dígitos.',
        ]);
    }

    /**
     * Verify OTP code.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'digits:6'],
        ]);

        $otp = $this->otpService->verify($request->email, $request->otp_code);

        if (! $otp) {
            return response()->json([
                'message' => 'Código inválido o expirado.',
            ], 422);
        }

        return response()->json([
            'message' => 'Código verificado.',
        ]);
    }

    /**
     * Resend OTP.
     */
    public function resendOtp(Request $request): JsonResponse
    {
        return $this->requestOtp($request);
    }

    /**
     * Request password reset (step 1 of 3).
     */
    public function passwordResetRequest(Request $request): JsonResponse
    {
        return $this->requestOtp($request);
    }

    /**
     * Verify OTP for password reset (step 2 of 3).
     */
    public function passwordResetVerify(Request $request): JsonResponse
    {
        return $this->verifyOtp($request);
    }

    /**
     * Confirm new password (step 3 of 3).
     * Requires valid OTP code — prevents account takeover.
     */
    public function passwordResetConfirm(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Verify OTP first — prevents password reset without email access
        $otp = $this->otpService->verify($request->email, $request->otp_code);
        if (! $otp) {
            return response()->json([
                'message' => 'Código inválido o expirado.',
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return response()->json([
                'message' => 'No se encontró la cuenta.',
            ], 404);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Revoke all tokens for security
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Contraseña restablecida exitosamente.',
        ]);
    }

    /**
     * Logout and revoke token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada.',
        ]);
    }

    /**
     * Get current user profile.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'phone' => $request->user()->phone,
                'birthday' => $request->user()->birthday,
            ],
        ]);
    }
}
