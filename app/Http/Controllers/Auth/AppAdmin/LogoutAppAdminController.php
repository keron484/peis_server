<?php

namespace App\Http\Controllers\Auth\AppAdmin;

use App\Http\Controllers\Controller;
use App\Services\ApiResponseService;
use App\Services\Auth\AppAdminAuth\LogoutAppAdminService;
use Illuminate\Http\Request;

class LogoutAppAdminController extends Controller
{
    //
    protected LogoutAppAdminService $logoutAppAdminService;
    public function logoutAppAdmin(Request $request){
         $this->logoutAppAdminService->logoutAppAdmin();
         return ApiResponseService::success("LOGOUT SUCCESSFULLY", null, null, 200);
    }
}
