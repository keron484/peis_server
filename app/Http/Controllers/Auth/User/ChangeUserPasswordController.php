<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Services\Auth\UserAuth\ChangeUserPasswordService;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\ApiResponseService;
use Illuminate\Http\Request;

class ChangeUserPasswordController extends Controller
{
    //
    protected ChangeUserPasswordService $changeUserPasswordService;
    public function __construct(ChangeUserPasswordService $changeUserPasswordService)
    {
        $this->changeUserPasswordService = $changeUserPasswordService;
    }
    public function changeUserPassword(ChangePasswordRequest $request)
    {
        try {
            $this->changeUserPasswordService->changeUserPassword($request, $request->all());
            return ApiResponseService::success("Password Changed Successfully", null, null, 200);
        } catch (\Exception $e) {
            return ApiResponseService::error($e->getMessage(), null, 500);
        }
    }
}
