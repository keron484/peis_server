<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Services\ApiResponseService;
use App\Services\Auth\UserAuth\LoginUserService;
use Exception;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    //
    protected LoginUserService $loginUserService;
    public function __construct(LoginUserService $loginUserService){
        $this->loginUserService = $loginUserService;
    }
    public function loginUser(LoginUserRequest $request){
         try{
           $loginUser = $this->loginUserService->loginUser($request->all());
            return ApiResponseService::success("OTP PASSWORD SENT SUCCESSFULLY", $loginUser, null, 200);
         }
         catch(Exception $e){
            return ApiResponseService::error($e->getMessage(), null, 500);
         }
    }
}
