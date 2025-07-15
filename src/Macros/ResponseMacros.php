<?php

namespace Corekit\Macros;

use Illuminate\Support\Facades\Response;

class ResponseMacros
{
    public static function register()
    {
        Response::macro('success', function ($data = [], $message = 'Success') {
            return Response::json([
                'status' => 'success',
                'message' => $message,
                'data' => $data,
            ]);
        });

        Response::macro('error', function ($message = 'Error', $code = 400) {
            return Response::json([
                'status' => 'error',
                'message' => $message,
            ], $code);
        });
    }
}