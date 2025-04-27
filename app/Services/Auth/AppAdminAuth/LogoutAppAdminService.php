<?php

namespace App\Services\Auth\AppAdminAuth;

class LogoutAppAdminService
{
    // Implement your logic here
    public function logoutAppAdmin()
    {
        $authUser = auth()->guard('appAdmin')->user();
        if ($authUser) {
            auth()->guard('appAdmin')->logout();
            return true;
        }
        return false;
    }
}
