<?php

namespace App\Services\Auth\AppAdminAuth;

class GetAuthAppAdminService
{
    // Implement your logic here
    public function getAuthAppAdmin(){
        $authUser = auth()->guard('appAdmin')->user();
        return $authUser;
    }
}
