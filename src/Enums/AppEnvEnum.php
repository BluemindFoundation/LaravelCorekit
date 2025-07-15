<?php

namespace Corekit\Enums;

enum AppEnvEnum: string
{
    case LOCAL = 'local';
    case DEVELOPMENT = 'development';
    case STAGING = 'staging';
    case PRODUCTION = 'production';
}