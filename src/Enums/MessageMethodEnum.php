<?php

namespace Corekit\Enums;

enum MessageMethodEnum: string
{
    case SMS = 'sms';
    case MAIL = 'email';
    case WHATSAPP = 'whatsapp';

    static function fromString(?string $value): ?MessageMethodEnum
    {
        return match ($value) {
            self::SMS->value => self::SMS,
            self::MAIL->value => self::MAIL,
            self::WHATSAPP->value => self::WHATSAPP,
            default => null,
        };
    }
}