<?php

namespace App\Services\Auth\UserAuth;

class GetAuthUserService
{
    // Implement your logic here
    public function getAuthUser($request){
       $authUser = $request->user();
        return $authUser;
    }
}
