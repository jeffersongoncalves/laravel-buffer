<?php

namespace JeffersonGoncalves\Buffer;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BufferServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-buffer')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Buffer::class, function () {
            return new Buffer(
                (string) config('buffer.base_url'),
                (string) config('buffer.access_token'),
            );
        });
    }
}
