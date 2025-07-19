<?php

namespace Corekit\Mappers;

use Corekit\Enums\ApiResponseStatusEnum;

class ApiResponseStatusEnumCodeMapper
{
    public static bool $forceHttp200 = false;

    public static function getHttpCode(ApiResponseStatusEnum $status): int
    {
        return self::$forceHttp200
            ? 200
            : match ($status) {
                ApiResponseStatusEnum::SUCCESS => 200,
                ApiResponseStatusEnum::VALIDATION_FAILED => 422,
                ApiResponseStatusEnum::NOT_FOUND => 404,
                ApiResponseStatusEnum::UNAUTHORIZED => 401,
                ApiResponseStatusEnum::FORBIDDEN => 403,
                ApiResponseStatusEnum::CONFLICT => 409,
                ApiResponseStatusEnum::BAD_REQUEST => 400,
                ApiResponseStatusEnum::OPERATION_FAILED,
                ApiResponseStatusEnum::OPERATION_NOT_COMPLETED => 400,
                ApiResponseStatusEnum::SERVER_ERROR => 500,
            };
    }
}