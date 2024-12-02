<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\ProductController;
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
// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/products/{productType}', [ProductController::class, 'index']);
});
