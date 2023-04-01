<?php
use Illuminate\Support\Facades\Route;
use App\Actions\LoginAction;
use App\Actions\RegisterAction;
use App\Actions\ForgotPasswordAction;
use App\Actions\ResetPasswordAction;
use App\Actions\EmailVerificationPromptAction;
use App\Actions\VerifyEmailAction;
use App\Actions\EmailVerificationNotificationAction;
use App\Actions\ConfirmablePasswordAction;
use App\Actions\AuthenticatedSessionAction;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterAction::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterAction::class, 'register']);

    Route::get('/login', [LoginAction::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginAction::class, 'login']);

    Route::get('/forgot-password', [ForgotPasswordAction::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordAction::class, 'forgot'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordAction::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordAction::class, 'reset'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptAction::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailAction::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationAction::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordAction::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordAction::class, 'store']);

    Route::post('logout', [AuthenticatedSessionAction::class, 'destroy'])
                ->name('logout');
});
