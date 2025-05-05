<?php

namespace Elfcms\Elfcms\Facades;

use Illuminate\Support\Facades\Facade;

class PageConfig extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pageconfig';
    }
}
