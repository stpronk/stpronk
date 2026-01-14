<?php

declare(strict_types=1);

namespace Stpronk\UrlDissector\Facades;

use Illuminate\Support\Facades\Facade;

class UrlDissector extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Stpronk\UrlDissector\Services\UrlDissectorService::class;
    }
}
