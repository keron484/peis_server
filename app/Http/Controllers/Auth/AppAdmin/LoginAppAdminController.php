<?php

namespace App\Http\Controllers\Auth\AppAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginAppAdminRequest;
use App\Services\ApiResponseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Auth\AppAdminAuth\LoginAppAdminService;
use Exception;
use Illuminate\Http\Request;

class LoginAppAdminController extends Controller
{
    //
    protected LoginAppAdminService $loginAppAdminService;
    public function __construct(LoginAppAdminService $loginAppAdminService){
        $this->loginAppAdminService = $loginAppAdminService;
    }
    public function loginAppAdmin(LoginAppAdminRequest $requesst){
         try{
            $otpTokenHeader = $this->loginAppAdminService->loginUser($requesst->validated());
            return ApiResponseService::success("OTP PASSWORD SENT SUCCESSFULLY", $otpTokenHeader, null, 200);
         }
         catch (ModelNotFoundException $e) {
            return ApiResponseService::error($e->getMessage(), null, 400);
        }
         catch(Exception $e){
            return ApiResponseService::error($e->getMessage(), null, 500);
         }
    }
}
