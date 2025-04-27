<?php

namespace App\Services\Auth\AppAdminAuth;
use App\Models\Otp;
use App\Models\PasswordResetToken;
use illuminate\Support\Str;
use Exception;
use Carbon\Carbon;
use App\Jobs\SendOtpTokenJob;
use App\Models\AppAdmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class AppAdminPasswordResetService
{
        /**
     * Initiates the password reset process by generating and storing an OTP.
     *
     * @param array $passwordResetData An array containing the AppAdmin's email.
     * Expected key: 'email'.
     *
     * @return array An array containing the OTP header and the OTP.
     * Keys: 'otp_header', 'otp'.
     *
     * @throws ModelNotFoundException If the AppAdmin with the provided email is not found.
     * @throws Exception If an error occurs during OTP generation or storage.
     */
    public function resetPassword(array $passwordResetData): array
    {
        try {
            $AppAdmin = AppAdmin::where('email', $passwordResetData['email'])->first();

            if (!$AppAdmin) {
                throw new ModelNotFoundException('Parent Not Found');
            }

            $otp = Str::random(6);
            $otpHeader = Str::random(24);
            $expiresAt = Carbon::now()->addMinutes(5);

            Otp::create([
                'token_header' => $otpHeader,
                'actorable_id' => $AppAdmin->id,
                'actorable_type' => AppAdmin::class,
                'otp' => $otp,
                'expires_at' => $expiresAt,
            ]);
             dispatch(new SendOtpTokenJob($otp, $AppAdmin->email));
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
                'actorable_type' => AppAdmin::class,
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
     * Changes the AppAdmin's password using the provided password reset token.
     *
     * @param array $passwordData An array containing the new password.
     * Expected key: 'new_password'.
     * @param string $passwordResetToken The password reset token.
     *
     * @throws ModelNotFoundException If the password reset token or the AppAdmin is not found.
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

            $AppAdmin = AppAdmin::findOrFail($passwordResetTokenRecord->actorable_id);

            $AppAdmin->password = Hash::make($passwordData['new_password']);
            $AppAdmin->save();

            $passwordResetTokenRecord->delete();
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
