<?php

namespace App\Services\Auth\UserAuth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserService
{
    // Implement your logic here
    public function createUser($userData){
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
        ]);
        return $user;
    }
}
