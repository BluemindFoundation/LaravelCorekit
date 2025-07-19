<?php

namespace Corekit\Facades;

use Illuminate\Support\Facades\Facade;

class HttpClientFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'corekit.httpclient';
    }
}