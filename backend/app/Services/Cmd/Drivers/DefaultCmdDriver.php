<?php

namespace App\Services\Cmd\Drivers;

use App\Services\Cmd\Contracts\CmdDriverContract;

class DefaultCmdDriver implements CmdDriverContract
{
    /**
     * Grep the value from file.
     *
     * @param $value
     * @param $file
     * @return array
     */
    public function grep($value, $file): array
    {
        exec("grep $value $file", $rows);

        return $rows;
    }

    /**
     * Search and replace a word in file.
     *
     * @param $search
     * @param $replace
     * @param $file
     * @return void
     */
    public function sed($search, $replace, $file): void
    {
        exec("sed -i s/$search/$replace/ $file");
    }

    /**
     * Append a row in a file.
     *
     * @param $value
     * @param $file
     * @return void
     */
    public function echo($value, $file): void
    {
        exec("echo $value >> $file");
    }
}
