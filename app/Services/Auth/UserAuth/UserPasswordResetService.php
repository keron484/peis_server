<?php

namespace App\Services\Auth\UserAuth;

use App\Jobs\SendOtpTokenJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\PasswordResetToken;
use App\Models\Otp;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
class UserPasswordResetService
{
     /**
     * Initiates the password reset process by generating and storing an OTP.
     *
     * @param array $passwordResetData An array containing the user's email.
     * Expected key: 'email'.
     *
     * @return array An array containing the OTP header and the OTP.
     * Keys: 'otp_header', 'otp'.
     *
     * @throws ModelNotFoundException If the user with the provided email is not found.
     * @throws Exception If an error occurs during OTP generation or storage.
     */
    public function resetPassword(array $passwordResetData): array
    {
        try {
            $user = User::where('email', $passwordResetData['email'])->first();

            if (!$user) {
                throw new ModelNotFoundException('Parent Not Found');
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
             dispatch(new SendOtpTokenJob($otp, $user->email));
            return ['otp_header' => $otpHeader];
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Verifies the provided OTP and generates a password reset token.
     *
     * @param string $otp The OTP to verify.
     * @param string $tokenHeader The OTP header.
     *
     * @return string The generated password reset token.
     *
     * @throws ModelNotFoundException If the OTP record is not found.
     * @throws Exception If the OTP is expired or has already been used, or if there's an error generating the password reset token.
     */
    public function verifyOtp(string $otp, string $tokenHeader): string
    {
        try {
            $otpRecord = Otp::where('otp', $otp)
                ->where('token_header', $tokenHeader)
                ->first();

            if (!$otpRecord) {
                throw new ModelNotFoundException('Invalid OTP token');
            }

            if ($otpRecord->isExpired()) {
                throw new Exception('Expired Otp token');
            }

            $otpRecord->update(['used' => true]);

            $passwordResetToken = Str::random(35);
            $expiresAt = Carbon::now()->addDay();

            PasswordResetToken::create([
                'token' => $passwordResetToken,
                'actorable_id' => $otpRecord->actorable_id,
                'actorable_type' => User::class,
                'expires_at' => $expiresAt,
            ]);

            $otpRecord->delete();

            return $passwordResetToken;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Changes the user's password using the provided password reset token.
     *
     * @param array $passwordData An array containing the new password.
     * Expected key: 'new_password'.
     * @param string $passwordResetToken The password reset token.
     *
     * @throws ModelNotFoundException If the password reset token or the user is not found.
     * @throws Exception If the password reset token is invalid or expired, or if there's an error updating the password.
     */
    public function changePassword(array $passwordData, string $passwordResetToken): void
    {
        try {
            $passwordResetTokenRecord = PasswordResetToken::where('token', $passwordResetToken)->first();

            if (!$passwordResetTokenRecord) {
                throw new ModelNotFoundException('Invalid Password Reset Token');
            }

             if ($passwordResetTokenRecord->expires_at < Carbon::now()) {
                throw new Exception('Password Reset Token has expired');
            }

            $user = User::findOrFail($passwordResetTokenRecord->actorable_id);

            $user->password = Hash::make($passwordData['new_password']);
            $user->save();

            $passwordResetTokenRecord->delete();
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
