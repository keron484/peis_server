<?php

namespace App\Services\Auth\UserAuth;

class LogoutUserService
{
    // Implement your logic here
    public function logoutUser($request)
    {
        $request->user()->currentAccessToken()->delete();
        return true;
    }
}
