<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Services\ApiResponseService;
use App\Services\Auth\UserAuth\GetAuthUserService;
use Exception;
use Illuminate\Http\Request;

class GetAuthUserController extends Controller
{
    //
    protected GetAuthUserService $getAuthUserService;
    public function __construct(GetAuthUserService $getAuthUserService)
    {
        $this->getAuthUserService = $getAuthUserService;
    }
    public function getAuthUser(Request $request){
        try{
           $authUser = $this->getAuthUserService->getAuthUser($request);
           return ApiResponseService::success("User Retrieved Successfully", $authUser, null, 200);
        }
        catch(Exception $e){
              return ApiResponseService::error($e->getMessage(), null, 500);
        }
    }
}
