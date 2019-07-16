<?php

namespace Hongxu\GitHubStorage\Facades;

use Hongxu\GitHubStorage\Components\StorageKernel;
use Illuminate\Support\Facades\Facade;

class GithubStorage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return StorageKernel::class;
    }

}