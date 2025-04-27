<?php

namespace App\Services\Auth\UserAuth;
use illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Otp;
use App\Jobs\SendOtpTokenJob;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
class LoginUserService
{
    /**
 * Attempts to log in a App User.
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
            $user = User::where('email', $loginData['email'])->first();

            if (!$user) {
                throw new ModelNotFoundException('User not found.');
            }

            if (!Hash::check($loginData['password'], $user->password)) {
                throw new Exception('Incorrect credentials.');
            }

            $otp = Str::random(6);
            $otpHeader = Str::random(24);
            $expiresAt = Carbon::now()->addMinutes(5);

            Otp::create([
                'token_header' => $otpHeader,
                'actorable_id' => $user->id,
                'actorable_type' => User::class,
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
