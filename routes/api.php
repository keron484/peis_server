<?php

use App\Http\Controllers\Auth\User\ChangeUserPasswordController;
use App\Http\Controllers\Auth\User\GetAuthUserController;
use App\Http\Controllers\Auth\User\ValidateOtpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\User\LoginController;
use App\Http\Controllers\Auth\User\LogoutUserController;
use App\Http\Controllers\Auth\User\UserPasswordResetController;
use App\Http\Controllers\Auth\AppAdmin\AppAdminPasswordResetController;
use App\Http\Controllers\Auth\AppAdmin\ChangeAppAdminPasswordController;
use App\Http\Controllers\Auth\AppAdmin\GetAuthAppAdminController;
use App\Http\Controllers\Auth\AppAdmin\LoginAppAdminController;
use App\Http\Controllers\Auth\AppAdmin\LogoutAppAdminController;
use App\Http\Controllers\Auth\AppAdmin\ValidateAppAdminOtpController;
use App\Http\Controllers\Auth\AppAdmin\CreateAppAdminController;
use App\Http\Controllers\CampaignController;

Route::prefix('v1/auth/user')->group(function () {
    Route::post('/login-user', [LoginController::class, 'loginUser']);
    Route::post('/validate-otp', [ValidateOtpController::class, 'validateUserOtp']);
    Route::middleware(['auth:sanctum'])->post('/logout-user', [LogoutUserController::class, 'logoutUser']);
    Route::middleware(['auth:sanctum'])->get('/get-auth-user', [GetAuthUserController::class, 'getAuthUser']);
    Route::middleware(['auth:sanctum'])->post('/change-password', [ChangeUserPasswordController::class, 'changeUserPassword']);
    Route::post('/request-password-reset-token', [UserPasswordResetController::class, 'resetPassword']);
    Route::post('/verify-password-reset-otp', [UserPasswordResetController::class, 'verifyOtp']);
    Route::post('/change-password-unauth', [UserPasswordResetController::class, 'resetUserPassword']);
});

Route::prefix('v1/auth/app-admin')->group(function ( ) {
    Route::post('/login-admin', [LoginAppAdminController::class, 'loginAppAdmins']);
    Route::post('/validate-otp', [ValidateAppAdminOtpController::class, 'validateAppAdminOtp']);
    Route::middleware(['auth:sanctum'])->post('/logout-admin', [LogoutAppAdminController::class, 'logoutAppAdmin']);
    Route::middleware(['auth:sanctum'])->get('/get-auth-admin', [GetAuthAppAdminController::class, 'getAuthAppAdmin']);
    Route::middleware(['auth:sanctum'])->post('/change-password', [ChangeAppAdminPasswordController::class, 'changePassword']);
    Route::post('/request-password-reset-token', [AppAdminPasswordResetController::class, 'resetPassword']);
    Route::post('/verify-password-reset-otp', [AppAdminPasswordResetController::class, 'verifyOtp']);
    Route::post('/change-password-unauth', [AppAdminPasswordResetController::class, 'changePassword']);
    Route::middleware(['auth:sanctum'])->post('/create-app-admin', [CreateAppAdminController::class, 'createAppAdmin']);
});

Route::prefix('v1/campaigns')->group( function() {
   Route::post('/create-campaign', [CampaignController::class, 'createCampaign']);
});

