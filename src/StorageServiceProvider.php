<?php

namespace Hongxu\GitHubStorage;

use Github\Client;
use Hongxu\GitHubStorage\Components\GithubStorageKernel;
use Hongxu\GitHubStorage\Components\StorageKernel;
use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->app->has(Client::class)) {
            $this->app->singleton(Client::class, function () {
                return new Client();
            });
        }

        $this->app->singleton(StorageKernel::class, function () {
            return new GithubStorageKernel(config('storage'));
        });
    }
}
