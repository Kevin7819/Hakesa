<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\OtpPasswordResetController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:10,1');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [OtpPasswordResetController::class, 'showRequestForm'])
        ->name('password.request');

    Route::post('forgot-password', [OtpPasswordResetController::class, 'sendOtp'])
        ->name('password.reset.otp.send')
        ->middleware('throttle:3,1');

    Route::get('verify-otp', [OtpPasswordResetController::class, 'showOtpForm'])
        ->name('password.reset.otp.verify');

    Route::post('verify-otp', [OtpPasswordResetController::class, 'verifyOtp'])
        ->name('password.reset.otp.verify.post')
        ->middleware('throttle:5,1');

    Route::get('reset-password/{token}', [OtpPasswordResetController::class, 'showNewPasswordForm'])
        ->name('password.reset.new');

    Route::post('reset-password', [OtpPasswordResetController::class, 'resetPassword'])
        ->name('password.reset.new.post')
        ->middleware('throttle:5,1');

    Route::post('resend-otp', [OtpPasswordResetController::class, 'resendOtp'])
        ->name('password.reset.otp.resend')
        ->middleware('throttle:3,1');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware('throttle:5,1');

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
