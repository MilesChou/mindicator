<?php

namespace App\Console\Commands;

use App\Models\Vcs\Commits;
use App\Models\Vcs\Repositories;
use App\Models\Vcs\Tags;
use App\Vcs\Git\CommitResolver;
use App\Vcs\Git\Factory as GitFactory;
use Composer\Repository\Vcs\VcsDriverInterface;
use Composer\Util\Platform;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RuntimeException;

class CommitCreate extends Command
{
    protected $signature = 'app:commit:create
                                {url : Git url}
                                {ref : Ref}
                                {--label=* : Label for commit}
                                {--clover= : Clover xml path}
                                {--disable-network : Disable network, just use cache or fail}
                                ';

    protected $description = 'Create Commit';

    public function handle(Repositories $repositories, GitFactory $gitFactory): int
    {
        $url = $this->argument('url');
        $ref = $this->argument('ref');
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

        $commitSha1 = (new CommitResolver($driver))->resolve($ref);

        /** @var Commits $commitEntity */
        $commitEntity = Commits::query()->firstOrNew([
            'sha1' => $commitSha1,
        ], [
            'repository_id' => $repository->id,
            'labels' => $labels,
        ]);

        $commitEntity->save();

        if ($commitSha1 !== $ref) {
            /** @var Tags $tagEntity */
            $tagEntity = Tags::query()->firstOrNew([
                'name' => $ref,
            ], [
                'repository_id' => $repository->id,
                'commit_sha1' => $commitSha1,
                'labels' => $labels,
            ]);

            $tagEntity->save();

            if ($clover) {
                $this->copyClover($clover, $tagEntity->cloverFile());
            }
        } else {
            if ($clover) {
                $this->copyClover($clover, $commitEntity->cloverFile());
            }
        }

        return self::SUCCESS;
    }

    private function copyClover(string $filename, string $target): void
    {
        $path = realpath($filename);

        if (File::missing($path) || !File::isFile($path)) {
            throw new RuntimeException('File not found');
        }

        File::ensureDirectoryExists(dirname($target));
        File::copy($path, $target);
    }
}
