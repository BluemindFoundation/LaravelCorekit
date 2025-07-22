<?php

namespace Corekit\Enums;

enum OTPTypeEnum: string
{
    case AUTHENTICATION = 'authentication';
    case PASSWORD_RESET = 'password-reset';
    case PROFILE_UPDATE = 'profile-update';

    public static function fromString(string $value): self
    {
        return match ($value) {
            'authentication'   => self::AUTHENTICATION,
            'password-reset'   => self::PASSWORD_RESET,
            'profile-update'   => self::PROFILE_UPDATE,
            default => throw new \InvalidArgumentException("Invalid OTP type: $value"),
        };
    }
}