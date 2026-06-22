<?php

namespace Tests;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Create the application and force an isolated in-memory SQLite
     * connection for tests.
     *
     * The Docker container injects DB_CONNECTION=pgsql as a real OS
     * environment variable, which takes precedence over phpunit.xml's
     * <env> entries. Without this override, RefreshDatabase would run
     * `migrate:fresh` against the live development PostgreSQL database
     * and wipe its data. Forcing the connection here guarantees tests
     * never touch PostgreSQL, regardless of environment variables.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('database.connections.sqlite.url', null);

        return $app;
    }
}
