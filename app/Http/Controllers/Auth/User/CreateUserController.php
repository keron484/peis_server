<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Services\ApiResponseService;
use App\Services\Auth\UserAuth\CreateUserService;
use Illuminate\Http\Request;

class CreateUserController extends Controller
{
    //
    protected CreateUserService $createUserService;
    public function __construct(CreateUserService $createUserService)
    {
        $this->createUserService = $createUserService;
    }

    public function createUser(CreateUserRequest $request){
       $createUser = $this->createUserService->createUser($request->all());
       return ApiResponseService::success("User Created Successfully", $createUser, null, 201);
    }
}
