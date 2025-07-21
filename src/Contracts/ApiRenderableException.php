<?php

namespace Corekit\Contracts;

use Illuminate\Http\JsonResponse;

interface ApiRenderableException
{
    public function toApiResponse(): JsonResponse;
}