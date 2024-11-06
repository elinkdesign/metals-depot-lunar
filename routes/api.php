<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/register', [RegisteredUserController::class, 'mobileRegister']);
Route::post('/login', [AuthenticatedSessionController::class, 'mobileLogin']);
Route::post('/mobile/verify-email', [RegisteredUserController::class, 'verifyEmailWithCode']);
// Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//     ->name('password.email');
// Route::post('/reset-password', [NewPasswordController::class, 'store'])
//     ->name('password.update');

// Protected routes
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/user', function (Request $request) {
//         return $request->user();
//     });
    
//     Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
//         ->name('logout');

//     Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//         ->middleware('throttle:6,1')
//         ->name('verification.send');

//     Route::post('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//         ->middleware(['signed', 'throttle:6,1'])
//         ->name('verification.verify');
// });