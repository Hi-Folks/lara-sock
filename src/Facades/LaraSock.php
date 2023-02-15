<?php

namespace HiFolks\LaraSock\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HiFolks\LaraSock\LaraSock
 */
class LaraSock extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \HiFolks\LaraSock\LaraSock::class;
    }
}
