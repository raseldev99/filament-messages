<?php

namespace Raseldev99\FilamentMessages\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Raseldev99\FilamentMessages\FilamentMessages
 */
class FilamentMessages extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Raseldev99\FilamentMessages\FilamentMessages::class;
    }
}
