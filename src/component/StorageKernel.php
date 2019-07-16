<?php

namespace Hongxu\GitHubStorage\Components;

interface StorageKernel
{

    public function put($fileName);

    public function find();

    public function check();

}