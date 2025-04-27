<?php

namespace App\Http\Controllers\Auth\AppAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeUnAuthPasswordRequest;
use App\Http\Requests\OtpRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\ApiResponseService;
use App\Services\Auth\AppAdminAuth\AppAdminPasswordResetService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Http\Request;

class AppAdminPasswordResetController extends Controller
{
    //
    protected AppAdminPasswordResetService $appAdminPasswordResetService;
    public function __construct(AppAdminPasswordResetService $appAdminPasswordResetService)
    {
        $this->appAdminPasswordResetService = $appAdminPasswordResetService;
    }
    public function resetPassword(ResetPasswordRequest $request) {
        try{
            $resetPasswordToken = $this->appAdminPasswordResetService->resetPassword($request->all());
            return ApiResponseService::success("Otp password sent to email successfully", $resetPasswordToken, null, 200);
        }
        catch (ModelNotFoundException $e) {
            return ApiResponseService::error($e->getMessage(), null, 400);
        }
        catch(Exception $e){
            return ApiResponseService::error($e->getMessage(), null, 400);
        }
    }

    public function validateOtp(OtpRequest $request){
        try{
            $tokenHeader = $request->header('OTP_TOKEN_HEADER');
            $passwordResetToken = $this->appAdminPasswordResetService->verifyOtp($request->otp, $tokenHeader);
            return ApiResponseService::success('Password reset token generated successfully', $passwordResetToken, null, 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponseService::error($e->getMessage(), null, 404);
        } catch (Exception $e) {
            return ApiResponseService::error($e->getMessage(), null, 500);
        }
    }

    public function changePassword(ChangeUnAuthPasswordRequest $request){
        try{
            $passwordResetToken = $request->header('PASSWORD_RESET_TOKEN');
            $this->appAdminPasswordResetService->changePassword($request->validated(), $passwordResetToken);
            return ApiResponseService::success('Password changed successfully', null, null, 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponseService::error($e->getMessage(), null, 404);
        } catch (Exception $e) {
            return ApiResponseService::error($e->getMessage(), null, 500);
        }
    }
}
