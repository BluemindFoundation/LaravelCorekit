<?php

namespace Corekit\Traits;

use Illuminate\Http\JsonResponse;
use Corekit\Enums\ApiResponseStatusEnum;
use Corekit\Mappers\ApiResponseStatusCodeMapper;
use Corekit\Utils\TranslationKeys\CommonTranslationKeys;
use Corekit\Utils\TranslatorUtil;
use Illuminate\Support\Facades\Response;

trait
HasApiResponse
{
    protected function apiResponse(
        bool $success,
        ApiResponseStatusEnum $status,
        mixed $data = [],
        array $extra = [],
        ?string $customMessage = null
    ): JsonResponse {
        // dd($customMessage);
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

    protected function failedResponse(mixed $data = [], ?string $message = null): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::OPERATION_FAILED, $data, [], $message);
    }

    protected function notCompletedResponse(?string $message = null): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::OPERATION_NOT_COMPLETED, [], [], $message);
    }

    protected function notFoundResponse(?string $message = null): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::NOT_FOUND, [], [], $message);
    }

    protected function unauthorizedResponse(?string $message = null): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::UNAUTHORIZED, [], [], $message);
    }

    protected function forbiddenResponse(?string $message = null): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::FORBIDDEN, [], [], $message);
    }

    protected function conflictResponse(?string $message = null): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::CONFLICT, [], [], $message);
    }

    protected function badRequestResponse(?string $message = null): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::BAD_REQUEST, [], [], $message);
    }

    protected function validationFailedResponse(array $errors = [], ?string $message = null): JsonResponse
    {
        return $this->apiResponse(false, ApiResponseStatusEnum::VALIDATION_FAILED, [], ['errors' => $errors], $message);
    }

    protected function serverErrorResponse(\Throwable $th = null, ?string $message = null): JsonResponse
    {
        // Optionally log $th
        return $this->apiResponse(false, ApiResponseStatusEnum::SERVER_ERROR, [], [], $message);
    }
}