<?php

namespace App\Services\Cmd\Contracts;

interface CmdDriverContract
{
    /**
     * Grep the value from file.
     *
     * @param $value
     * @param $file
     * @return array
     */
    public function grep($value, $file);

    /**
     * Search and replace a word in file.
     *
     * @param $search
     * @param $replace
     * @param $file
     * @return void
     */
    public function sed($search, $replace, $file);

    /**
     * Append a row in a file.
     *
     * @param $value
     * @param $file
     * @return void
     */
    public function echo($value, $file);
}