<?php

namespace App\Console\Commands;

use App\Models\Commits;
use App\Models\Repositories;
use App\Vcs\Git\Factory as GitFactory;
use Composer\Pcre\Preg;
use Composer\Repository\Vcs\VcsDriverInterface;
use Composer\Util\Platform;
use Composer\Util\ProcessExecutor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RuntimeException;

class CommitCreate extends Command
{
    protected $signature = 'app:commit:create
                                {url : Git url}
                                {commit : Commit id or tag}
                                {--label=* : Label for commit}
                                {--clover= : Clover xml path}
                                {--disable-network : Disable network, just use cache or fail}
                                ';

    protected $description = 'Create Commit';

    public function handle(Repositories $repositories, GitFactory $gitFactory): int
    {
        $url = $this->argument('url');
        $commit = $this->argument('commit');
        $clover = $this->option('clover');
        $labels = $this->option('label');

        if ($this->option('disable-network')) {
            Platform::putEnv('COMPOSER_DISABLE_NETWORK', true);
        }

        /** @var Repositories $repository */
        $repository = $repositories->newQuery()
            ->where('url', $url)
            ->firstOrFail();

        $repo = $gitFactory->create($repository->url);

        /** @var VcsDriverInterface $driver */
        $driver = $repo->getDriver();

        try {
            $changeDate = $driver->getChangeDate($commit);
        } catch (\Throwable $e) {
            // Throw exception when commit not found.
            throw $e;
        }

        /** @var Commits $commitEntity */
        $commitEntity = Commits::query()->firstOrNew([
            'repository_id' => $repository->id,
        ], [
            'sha1' => $commit,
            'labels' => $labels,
        ]);

        $commitEntity->save();

        if ($clover) {
            $this->copyClover($clover, $commitEntity);
        }

        return self::SUCCESS;
    }

    private function copyClover(string $filename, Commits $commit): void
    {
        $source = realpath($filename);

        if (File::missing($source)) {
            throw new RuntimeException('File not found');
        }

        $target = $commit->cloverFile();

        File::ensureDirectoryExists(dirname($target));
        File::copy($source, $target);
    }
}
