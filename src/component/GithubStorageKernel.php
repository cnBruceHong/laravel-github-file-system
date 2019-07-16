<?php

namespace Hongxu\GitHubStorage\Components;

use Exception;
use Github\Client;
use phpDocumentor\Reflection\File;
use RuntimeException;

class GithubStorageKernel implements StorageKernel
{

    const GITHUB_API = 'https://api.github.com/';

    const IMG_DOMAIN = 'https://raw.githubusercontent.com/:owner/:repo/:branch/:path/:filename';

    /**
     * config for github storage kernel
     *
     * @var array
     */
    private $config = [
        'owner'  => '',
        'repo'   => '',
        'branch' => '',
        'token'  => '',
        'path'   => '',
        'domain' => '',
        'rename' => false,
    ];

    /**
     * __construct is GithubStorageKernel constructor.
     *
     * @param array $config
     *
     */
    public function __construct(array $config = [])
    {
        $_config = array_merge([
            'repo'   => '',
            'branch' => 'master',
            'token'  => '',
            'path'   => '/',
            'domain' => 'https://github.com',
            'rename' => false,
        ], $config);

        if (strlen($_config['branch']) <= 0) {
            throw new RuntimeException('an error occur! please check the branch config');
        }

        if (strlen($_config['token']) <= 0) {
            throw new RuntimeException('an error occur! please check the token config');
        }

        if (strlen($_config['repo']) <= 0) {
            throw new RuntimeException('an error occur! please check the repo config');
        }

        $_config['repo'] = trim($_config['repo']);
        preg_match("/^(?<owner>\S+)\/(?<repo>\S+)$/", $_config['repo'], $match);
        if (count($match) <= 0) {
            throw new RuntimeException('an error occur! please check the repo config');
        }

        $this->config = $_config;
        $this->config['owner'] = $match['owner'];
        $this->config['repo'] = $match['repo'];

    }

    /**
     * Put a file to the storage.
     *
     * @param $fileName
     *
     * @return bool|void
     *
     * @throws Exception
     */
    public function put($fileName)
    {
        if (!is_string($fileName)) {
            return false;
        }

        if (!file_exists($fileName) || !is_readable($fileName)) {
            throw new RuntimeException($fileName . ' dose not exist or readable!');
        }
        $content = file_get_contents($fileName);

        $client = app(Client::class);
        $client->authenticate($this->config['token'], Client::AUTH_HTTP_TOKEN);

        if ($this->config['rename']) {
            $suffix = substr($fileName, strrpos($fileName, '.'));
            $fileName = date('YmdHis', time()) . $suffix;
        }
        $fileName = trim($this->config['path'] . $fileName, ' \t\n\r\0\x0B/');
        if (strlen($fileName) <= 0) {
            throw new RuntimeException('path or filename is invalid!');
        }

        $fileInfo = $client->api('repo')->contents()->create(
            $this->config['owner'],
            $this->config['repo'],
            $fileName,
            $content,
            'upload by laravel-github-storage',
            $this->config['branch'],
            [
                'name'  => 'LaravelGithubStorageRobot',
                'email' => 'LaravelGithubStorageRobot@bruceit.com',
            ]
        );

        return $fileInfo['content']['download_url'];
    }

    /**
     * @param $name
     */
    public function find()
    {

    }

    /**
     *
     */
    public function check()
    {

    }

    /**
     *
     */
    private function upload()
    {

    }
}