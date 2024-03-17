<?php

namespace App\Console\Commands;

use App\Models\Repositories;
use App\Vcs\Git\Factory as GitFactory;
use Composer\Repository\Vcs\VcsDriverInterface;
use Illuminate\Console\Command;

class RepositoryCreate extends Command
{
    protected $signature = 'app:repository:create {--tag=*} {url}';

    protected $description = 'Create Repository';

    public function handle(Repositories $repositories, GitFactory $gitFactory): int
    {
        $url = $this->argument('url');
        $tags = $this->option('tag');

        /** @var Repositories $repository */
        $repository = $repositories->newQuery()->firstOrNew([
            'url' => $url,
        ], [
            'private_key' => '',
            'tags' => json_encode($tags),
        ]);

        $repo = $gitFactory->create($repository->url);

        /** @var VcsDriverInterface $driver */
        $driver = $repo->getDriver();

        dd($driver->getTags());


        return self::SUCCESS;
    }
}
