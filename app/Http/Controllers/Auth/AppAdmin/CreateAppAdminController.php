<?php

namespace App\Http\Controllers\Auth\AppAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAppAdminRequest;
use App\Services\ApiResponseService;
use App\Services\Auth\AppAdminAuth\CreateAppAdminService;
use Illuminate\Http\Request;

class CreateAppAdminController extends Controller
{
    //
    protected CreateAppAdminService $createAppAdminService;
    public function createAppAdmin(CreateAppAdminRequest $request){
        $createAppAdmin = $this->createAppAdminService->createAppAdmin($request->validated());
        return ApiResponseService::success("App Admin Created Successfully", $createAppAdmin, null, 200);
    }
}
