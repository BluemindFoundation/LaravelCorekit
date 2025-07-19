<?php

namespace Corekit\Traits;

use Illuminate\Http\JsonResponse;
use Corekit\Enums\ApiResponseStatusEnum;
use Corekit\Mappers\ApiResponseStatusCodeMapper;
use Corekit\Utils\TranslationKeys\CommonTranslationKeys;
use Corekit\Utils\TranslatorUtil;
use Illuminate\Support\Facades\Response;

trait HasApiResponse
{
    protected function apiResponse(
        bool $success,
        ApiResponseStatusEnum $status,
        mixed $data = [],
        array $extra = [],
        ?string $customMessage = null
    ): JsonResponse {
        $message = $customMessage ?? TranslatorUtil::message($status->value);

        $response = array_merge([
            'success' => $success,
            'status' => $status->value,
            'message' => $message,
            'data' => $data,
        ], $extra);

        return Response::json($response, ApiResponseStatusCodeMapper::getHttpCode($status));
    }

    protected function successResponse(mixed $data = [], ?string $message = CommonTranslationKeys::OPERATION_SUCCESS): JsonResponse
    {
        return $this->apiResponse(true, ApiResponseStatusEnum::SUCCESS, $data, [], $message);
    }

    protected function failedResponse(mixed $data = [], ?string $message = CommonTranslationKeys::OPERATION_FAILURE): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::OPERATION_FAILED, $data, [], $message);
    }

    protected function notCompletedResponse(?string $message = CommonTranslationKeys::OPERATION_NOT_COMPLETED): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::OPERATION_NOT_COMPLETED, [], [], $message);
    }

    protected function notFoundResponse(?string $message = CommonTranslationKeys::NOT_FOUND): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::NOT_FOUND, [], [], $message);
    }

    protected function unauthorizedResponse(?string $message = CommonTranslationKeys::UNAUTHORIZED): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::UNAUTHORIZED, [], [], $message);
    }

    protected function forbiddenResponse(?string $message = CommonTranslationKeys::FORBIDDEN): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::FORBIDDEN, [], [], $message);
    }

    protected function conflictResponse(?string $message = CommonTranslationKeys::CONFLICT): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::CONFLICT, [], [], $message);
    }

    protected function badRequestResponse(?string $message = CommonTranslationKeys::BAD_REQUEST): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::BAD_REQUEST, [], [], $message);
    }

    protected function validationFailedResponse(array $errors = [], ?string $message = CommonTranslationKeys::VALIDATION_FAILED): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::VALIDATION_FAILED, [], ['errors' => $errors], $message);
    }

    protected function serverErrorResponse(\Throwable $th = null, ?string $message = CommonTranslationKeys::SERVER_ERROR): JsonResponse
    {
        // Optionally log $th
        return $this->apiResponse(false, ApiResponseStatusEnum::SERVER_ERROR, [], [], $message);
    }
}