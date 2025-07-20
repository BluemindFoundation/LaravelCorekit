<?php

namespace Corekit\Enums;

enum OTPTypeEnum: string
{
    case AUTHENTICATION = 'authentication';
    case RESET_PASSWORD = 'reset-password';
}