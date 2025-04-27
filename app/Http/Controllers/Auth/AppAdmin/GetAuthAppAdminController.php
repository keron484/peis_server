<?php

namespace App\Http\Controllers\Auth\AppAdmin;

use App\Http\Controllers\Controller;
use App\Services\ApiResponseService;
use App\Services\Auth\AppAdminAuth\GetAuthAppAdminService;
use Illuminate\Http\Request;

class GetAuthAppAdminController extends Controller
{
    //
    protected GetAuthAppAdminService $getAuthAppAdminService;
    public function getAuthAppAdmin(Request $request){
        $authAppAdmin = $this->getAuthAppAdminService->getAuthAppAdmin();
        return ApiResponseService::success("Authenticated App Admin Fetched Successfully", $authAppAdmin, null, 200);
    }
}
