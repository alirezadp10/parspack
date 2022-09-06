<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Set the currently logged in user for the application.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string|null  $guard
     * @return $this
     */
    public function actingAs(Authenticatable $user, $guard = null): TestCase|static
    {
        /** @var string $token jwt token generated for user */
        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer ' . $token);

        return $this;
    }

    protected function switchDBToMysql()
    {
        $this->artisan('migrate:fresh');

        $_ENV['DB_CONNECTION'] = 'mysql';

        $_ENV['DB_DATABASE'] = 'laravel_test';

        Config::set('database.connections.mysql.database', 'laravel_test');

        DB::setDefaultConnection('mysql');

        $this->artisan('migrate:fresh');
    }

    protected function switchDBToSqlite()
    {
        $this->artisan('migrate:fresh');

        $_ENV['DB_CONNECTION'] = 'sqlite';

        $_ENV['DB_DATABASE'] = ':memory:';

        Config::set('database.connections.mysql.database', ':memory:');

        DB::setDefaultConnection('sqlite');

        $this->artisan('migrate:fresh');
    }

    protected function switchCacheToRedis()
    {
        $_ENV['CACHE_DRIVER'] = 'redis';
    }

    protected function switchCacheToArray()
    {
        $_ENV['CACHE_DRIVER'] = 'array';
    }
}
