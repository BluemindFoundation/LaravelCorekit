<?php

namespace Corekit\Utils;

use Illuminate\Http\Request;

class OTPGeneratorUtil
{

    public function __construct(protected Request $request) {}


    public function __invoke(int $length = 6): string
    {
        $login = $this->request->input('login');
        $dialCode = $this->request->input('dial_code');

        $testOTP = $this->getTestOTP($login, $dialCode);

        if ($testOTP !== null) {
            return $testOTP;
        }

        return $this->generateRandomOTP($length);
    }


    protected function getTestOTP(string $login, ?string $dialCode): ?string
    {
        // $testUsers = config('test_data.test_users');
        // $testDialCode = config('test_data.test_dial_code'); // Dial code commun pour les numéros de test
        $testUsers = [];
        $testDialCode = '';
        foreach ($testUsers as $user) {
            if ($login === $user['email']) {
                return $user['otp'];
            }

            if ($dialCode && $testDialCode) {
                $fullPhoneNumber = $dialCode . $login; // Combiner le dial code et le numéro de téléphone
                $testFullPhoneNumber = $testDialCode . $user['phone_number']; // Combiner le dial code et le numéro de test

                if ($fullPhoneNumber === $testFullPhoneNumber) {
                    return $user['otp'];
                }
            }
        }

        return null;
    }

    protected function generateRandomOTP(int $length): string
    {
        $characters = '0123456789';
        $OTPCode = '';

        for ($i = 0; $i < $length; $i++) {
            $OTPCode .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $OTPCode;
    }
}