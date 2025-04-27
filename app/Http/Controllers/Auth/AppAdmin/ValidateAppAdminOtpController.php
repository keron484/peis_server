<?php

namespace App\Http\Controllers\Auth\AppAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpRequest;
use App\Services\ApiResponseService;
use App\Services\Auth\AppAdminAuth\ValidateAppAdminOtpService;
use Exception;
use Illuminate\Http\Request;

class ValidateAppAdminOtpController extends Controller
{
    //
    protected ValidateAppAdminOtpService $validateAppAdminOtpService;
    public function validateAppAdminOtp(OtpRequest $request){
        $tokenHeader = $request->header('OTP_TOKEN_HEADER');
       try{
        $authToken = $this->validateAppAdminOtpService->validateAppAdminOtp($tokenHeader, $request->otp);
        return ApiResponseService::success("Otp verification Successfully", $authToken, null, 200);
       }
       catch(Exception $e){
        return ApiResponseService::error($e->getMessage(), null, 500);
       }
    }
}
