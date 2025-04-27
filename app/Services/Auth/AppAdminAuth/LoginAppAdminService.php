<?php

namespace App\Services\Auth\AppAdminAuth;

use App\Models\AppAdmin;
use App\Models\Otp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendOtpTokenJob;
use Carbon\Carbon;
use Exception;
class LoginAppAdminService
{
        /**
 * Attempts to log in a student.
 *
 * @param array $loginData An array containing the user's email and password.
 * Expected keys: 'email', 'password'.
 *
 * @return string The OTP header on successful login.
 *
 * @throws ModelNotFoundException If the user with the provided email is not found.
 * @throws Exception If an error occurs during the login process, including incorrect credentials.
 */
public function loginUser($loginData){
    try {
        $appAdmin = AppAdmin::where('email', $loginData['email'])->first();

        if (!$appAdmin) {
            throw new ModelNotFoundException('User not found.');
        }

        if (!Hash::check($loginData['password'], $appAdmin->password)) {
            throw new Exception('Incorrect credentials.');
        }

        $otp = Str::random(6);
        $otpHeader = Str::random(24);
        $expiresAt = Carbon::now()->addMinutes(5);

        Otp::create([
            'token_header' => $otpHeader,
            'actorable_id' => $appAdmin->id,
            'actorable_type' => AppAdmin::class,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);

        dispatch(new SendOtpTokenJob($otp, $loginData['email']));

        return $otpHeader;
    } catch (ModelNotFoundException $e) {
        throw $e;
    } catch (Exception $e) {
        throw $e;
    }
}
}
