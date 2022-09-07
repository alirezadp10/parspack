<?php

namespace App\Services\Cmd\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static grep($value, $file)
 * @method static echo ($value, $file)
 * @method static sed($search, $replace, $file)
 * @see \App\Services\Cmd\CmdManager
 * @see \App\Services\Cmd\Contracts\CmdDriverContract
 */
class Cmd extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cmd';
    }
}