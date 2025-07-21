<?php

namespace Corekit\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Corekit\Contracts\ApiRenderableException;
use Corekit\Traits\HasApiResponse;

abstract class BaseApiException extends Exception implements ApiRenderableException
{

    use HasApiResponse;

    public function toApiResponse(): JsonResponse
    {
        return $this->failedResponse();
    }
}