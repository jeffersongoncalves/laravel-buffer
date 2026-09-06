<?php

namespace JeffersonGoncalves\Buffer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JeffersonGoncalves\Buffer\Buffer
 */
class Buffer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\Buffer\Buffer::class;
    }
}
