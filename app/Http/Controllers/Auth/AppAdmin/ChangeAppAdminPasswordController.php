<?php

namespace App\Http\Controllers\Auth\AppAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\Auth\AppAdminAuth\ChangeAppAdminPasswordService;
use App\Services\ApiResponseService;
use Exception;
use Illuminate\Http\Request;

class ChangeAppAdminPasswordController extends Controller
{
    //
    protected ChangeAppAdminPasswordService $changeAppAdminPasswordService;
    public function __construct(ChangeAppAdminPasswordService $changeAppAdminPasswordService)
    {
        $this->changeAppAdminPasswordService = $changeAppAdminPasswordService;
    }

    public function changePassword(ChangePasswordRequest $request)
    {
       try{
         $this->changeAppAdminPasswordService->changeUserPassword($request->validated());
         return ApiResponseService::success("Password Changed Successfully", null, null, 200);
       }
       catch(Exception $e){
        return ApiResponseService::error($e->getMessage(), null, 500);
       }
    }
}
