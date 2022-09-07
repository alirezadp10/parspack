<?php

namespace App\Services\Cmd;

use App\Services\Cmd\Contracts\CmdDriverContract;
use App\Services\Cmd\Drivers\DefaultCmdDriver;
use Illuminate\Support\Manager;

class CmdManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('cmd.driver');
    }

    /**
     * Return new instance of default driver
     *
     * @return CmdDriverContract
     */
    public function createDefaultDriver(): CmdDriverContract
    {
        return new DefaultCmdDriver();
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        foreach ($parameters as $key => $parameter) {
            $parameters[$key] = escapeshellarg($parameter);
        }

        return $this->driver()->$method(...$parameters);
    }
}
