<?php

namespace RezaulHReza\AiService\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RezaulHReza\AiService\AiService
 */
class AiService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \RezaulHReza\AiService\AiService::class;
    }
}
