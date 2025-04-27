<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpRequest;
use App\Services\ApiResponseService;
use App\Services\Auth\UserAuth\ValidateUserOtpService;
use Illuminate\Http\Request;
use Exception;
class ValidateOtpController extends Controller
{
    //
    protected ValidateUserOtpService $validateUserOtpService;
    public function __construct(ValidateUserOtpService $validateUserOtpService)
    {
        $this->validateUserOtpService = $validateUserOtpService;
    }

    public function validateUserOtp(OtpRequest $request)
    {
        $tokenHeader = $request->header('OTP_TOKEN_HEADER');
        try{
            $authToken =  $this->validateUserOtpService->validateUserOtp($tokenHeader, $request->otp);
            return ApiResponseService::success("User OTP Validated Successfully", $authToken, null, 200);
        } catch (Exception $e) {
            return ApiResponseService::error($e->getMessage(), null, 500);
        }
    }
}
