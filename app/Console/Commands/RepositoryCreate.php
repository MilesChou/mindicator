<?php

namespace App\Console\Commands;

use App\Models\Vcs\Repositories;
use App\Vcs\Git\Factory as GitFactory;
use Composer\Repository\Vcs\VcsDriverInterface;
use Composer\Util\Platform;
use Illuminate\Console\Command;

class RepositoryCreate extends Command
{
    protected $signature = 'app:repository:create
                                {url : Git Url}
                                {--label=* : Label for repositories}
                                {--disable-network : Disable network, just use cache or fail}
                                ';

    protected $description = 'Create Repository';

    public function handle(Repositories $repositories, GitFactory $gitFactory): int
    {
        $url = $this->argument('url');
        $labels = $this->option('label');

        if ($this->option('disable-network')) {
            Platform::putEnv('COMPOSER_DISABLE_NETWORK', true);
        }

        /** @var Repositories $repository */
        $repository = $repositories->newQuery()->firstOrNew([
            'url' => $url,
        ], [
            'private_key' => '',
            'labels' => json_encode($labels),
        ]);

        $repository->save();

        $repo = $gitFactory->create($repository->url);

        /** @var VcsDriverInterface $driver */
        $driver = $repo->getDriver();

        dd($driver->getTags());

        return self::SUCCESS;
    }
}
