<?php

namespace Signifly\Struct\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Struct extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'struct';
    }
}
