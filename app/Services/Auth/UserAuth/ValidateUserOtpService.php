<?php

namespace App\Services\Auth\UserAuth;

use App\Models\Otp;
use App\Models\User;
use Exception;

class ValidateUserOtpService
{
    public function validateUserOtp($tokenHeader, $otp)
    {
        try {
            $otpRecord = Otp::where('otp', $otp)
                ->where('token_header', $tokenHeader)
                ->first();

            if (!$otpRecord) {
                throw new Exception("Invalid Otp token");
            }

            if ($otpRecord->isExpired()) {
                throw new Exception("Expired Otp token");
            }

            $user = User::where('id', $otpRecord->actorable_id)->first();

            $token = $user->createToken('appUser')->plainTextToken;

            $otpRecord->delete();

            return ['authToken' =>  $token];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
