<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Cache;

class TwoFactorService
{
    private $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    public function getQRCodeUrl(User $user, string $secret): string
    {
        return $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
    }

    public function verifyCode(string $secret, string $code): bool
    {
        return $this->google2fa->verifyKey($secret, $code);
    }

    public function enable2FA(User $user, string $code): bool
    {
        if (!$this->verifyCode($user->two_factor_secret, $code)) {
            return false;
        }

        $user->update([
            'two_factor_enabled' => true,
            'two_factor_recovery_codes' => $this->generateRecoveryCodes()
        ]);

        return true;
    }

    public function disable2FA(User $user): void
    {
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null
        ]);
    }

    public function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(md5(random_bytes(16)), 0, 8));
        }
        return $codes;
    }

    public function verifyRecoveryCode(User $user, string $code): bool
    {
        $codes = $user->two_factor_recovery_codes ?? [];
        
        if (in_array(strtoupper($code), $codes)) {
            // Remove used code
            $codes = array_diff($codes, [strtoupper($code)]);
            $user->update(['two_factor_recovery_codes' => array_values($codes)]);
            return true;
        }

        return false;
    }
}