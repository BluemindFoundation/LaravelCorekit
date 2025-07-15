<?php

namespace Corekit\Enums;

enum OTPTypeEnum: string
{
    case REGISTRATION = 'registration';
    case RESET_PASSWORD = 'reset-password';
    case LOGIN = 'login';
}