<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Services\ApiResponseService;
use App\Services\Auth\UserAuth\LogoutUserService;
use Illuminate\Http\Request;
use Exception;
class LogoutUserController extends Controller
{
    //
    protected LogoutUserService $logoutUserService;
    public function __construct(LogoutUserService $logoutUserService)
    {
        $this->logoutUserService = $logoutUserService;
    }

    public function logoutUser(Request $request)
    {
        try {
            $this->logoutUserService->logoutUser($request);
            return ApiResponseService::success("User Logged Out Successfully", null, null, 200);
        } catch (Exception $e) {
            return ApiResponseService::error($e->getMessage(), null, 500);
        }
    }
}
