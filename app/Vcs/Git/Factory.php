<?php

namespace App\Vcs\Git;

use App\Vcs\ComposerDriverWrapper;
use App\Vcs\VcsInterface;
use Composer\Config;
use Composer\Factory as ComposerFactory;
use Composer\IO\NullIO;
use Composer\Repository\VcsRepository;

class Factory
{
    public function create(string $url): VcsInterface
    {
        $repoConfig = [
            'url' => $url,
        ];

        $io = $this->createIO();
        $config = $this->createConfig();
        $httpDownloader = ComposerFactory::createHttpDownloader($io, $config);

        $driver = (new VcsRepository($repoConfig, $io, $config, $httpDownloader))->getDriver();

        return ComposerDriverWrapper::wrap($driver);
    }

    private function createConfig(): Config
    {
        $baseDir = storage_path('app/vcs');

        return tap(new Config(baseDir: $baseDir), function (Config $config) use ($baseDir) {
            $config->merge(['config' => ['home' => $baseDir]]);
        });
    }

    public function createIO(): NullIO
    {
        return new NullIO();
    }
}
