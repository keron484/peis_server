<?php

namespace App\Services\Auth\AppAdminAuth;

use App\Jobs\SendAppAdminPasswordJob;
use App\Models\AppAdmin;
use Exception;
use Illuminate\Support\Facades\Hash;

class CreateAppAdminService
{
    // Implement your logic here
    public function createAppAdmin($appAdminData){
        try{
            $randomPassword = $this->generateRandomPassword();
            $appAdmin = new AppAdmin();
            $appAdmin->name = $appAdminData['username'];
            $appAdmin->email = $appAdminData['email'];
            $appAdmin->password = Hash::make($randomPassword);
            $appAdmin->save();
            dispatch(new SendAppAdminPasswordJob($appAdminData['email'], $randomPassword));
            return $appAdmin;

        }
        catch(Exception $e){
            throw $e;
        }
    }

    private function generateRandomPassword($length = 12) {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()-_=+[]{}|;:,.<>?';

        $allCharacters = $uppercase . $lowercase . $numbers . $specialChars;
        $password = '';
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $specialChars[rand(0, strlen($specialChars) - 1)];
        for ($i = 4; $i < $length; $i++) {
            $password .= $allCharacters[rand(0, strlen($allCharacters) - 1)];
        }

        $password = str_shuffle($password);

        return $password;
    }
}
