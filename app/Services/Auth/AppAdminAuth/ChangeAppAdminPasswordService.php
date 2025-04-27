<?php

namespace App\Services\Auth\AppAdminAuth;
use Exception;
use illuminate\Support\Facades\Hash;
class ChangeAppAdminPasswordService
{
    // Implement your logic here

    public function changeUserPassword($passwordData){
        try{
            $authUser = auth()->guard('appAdmin')->user();

        if (!$this->checkCurrentPassword($authUser, $passwordData["current_password"])) {
           throw new Exception("Current password is incorrect");
        }

        if ($this->updatePassword($authUser, $passwordData["new_password"])) {
            return true;
        }
        }
        catch(Exception $e){
            throw $e;
        }
    }

    protected function checkCurrentPassword($authUser, string $currentPassword): bool
    {
        return Hash::check($currentPassword, $authUser->password);
    }

    protected function updatePassword($authUser, string $newPassword): bool
    {
        $authUser->password = Hash::make($newPassword);
        return $authUser->save();
    }
}
