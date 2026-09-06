<?php

namespace JeffersonGoncalves\Buffer\Tests;

use JeffersonGoncalves\Buffer\BufferServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BufferServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('buffer.base_url', 'https://api.bufferapp.com/1');
        $app['config']->set('buffer.access_token', 'test-access-token');
    }
}
