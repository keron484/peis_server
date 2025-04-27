<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeUnAuthPasswordRequest;
use App\Http\Requests\OtpRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\ApiResponseService;
use App\Services\Auth\UserAuth\UserPasswordResetService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class UserPasswordResetController extends Controller
{
    //
    protected UserPasswordResetService $userPasswordResetService;
    public function __construct(UserPasswordResetService $userPasswordResetService)
    {
        $this->userPasswordResetService = $userPasswordResetService;
    }
    /**
     * Initiates the password reset process by generating and storing an OTP.
     *
     * @param Request $request The incoming request containing the user's email.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success or failure.
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $resetToken = $this->userPasswordResetService->resetPassword($request->validated());
            return ApiResponseService::success('Password reset token generated successfully', $resetToken, null, 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponseService::error($e->getMessage(), null, 404);
        } catch (Exception $e) {
            return ApiResponseService::error($e->getMessage(), null, 500);
        }
    }

    /**
     * Verifies the provided OTP and generates a password reset token.
     *
     * @param Request $request The incoming request containing the OTP and new password.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success or failure.
     */
    public function verifyOtp(OtpRequest $request)
    {
        try {
            $tokenHeader = $request->header('OTP_TOKEN_HEADER');
            $passwordResetToken =  $this->userPasswordResetService->verifyOtp($request->otp, $tokenHeader, );
            return ApiResponseService::success('Password reset token generated successfully', $passwordResetToken, null, 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponseService::error($e->getMessage(), null, 404);
        } catch (Exception $e) {
            return ApiResponseService::error($e->getMessage(), null, 500);
        }
    }

    /**
     * Resets the user's password using the provided token and new password.
     *
     * @param Request $request The incoming request containing the token and new password.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success or failure.
     */
    public function resetUserPassword(ChangeUnAuthPasswordRequest $request){
        try {
            $passwordResetToken = $request->header('PASSWORD_RESET_TOKEN');
            $this->userPasswordResetService->changePassword($request->validated(), $passwordResetToken);
            return ApiResponseService::success('Password reset successfully', null, null, 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponseService::error($e->getMessage(), null, 404);
        } catch (Exception $e) {
            return ApiResponseService::error($e->getMessage(), null, 500);
        }
    }
}
