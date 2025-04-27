<?php

namespace App\Services\Auth\AppAdminAuth;
use App\Models\Otp;
use App\Models\AppAdmin;
use Exception;
class ValidateAppAdminOtpService
{
    // Implement your logic here
    public function validateAppAdminOtp($tokenHeader, $otp)
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

            $user = AppAdmin::where('id', $otpRecord->actorable_id)->first();

            $token = $user->createToken('appAdmin')->plainTextToken;

            $otpRecord->delete();

            return ['authToken' =>  $token];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
