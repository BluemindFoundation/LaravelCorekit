<?php

namespace Corekit\Enums;

enum OTPTypeEnum: string
{
    case AUTHENTICATION = 'authentication';
    case RESET_PASSWORD = 'reset-password';

    public static function fromString(string $value): self
    {
        return match ($value) {
            'authentication'   => self::AUTHENTICATION,
            'reset-password'   => self::RESET_PASSWORD,
            default => throw new \InvalidArgumentException("Invalid OTP type: $value"),
        };
    }
}