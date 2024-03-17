<?php

namespace App\Console\Commands;

use App\Models\Vcs\Commits;
use App\Models\Vcs\Repositories;
use App\Models\Vcs\Tags;
use Illuminate\Console\Command;

class CommitCloverOverview extends Command
{
    protected $signature = 'app:commit:clover:overview
                                {url}
                                {ref}';

    protected $description = 'Show the commit overview';

    public function handle(): int
    {
        /** @var Repositories $repository */
        $repository = Repositories::query()
            ->where('url', $this->argument('url'))
            ->firstOrFail();

        /** @var Tags $tag */
        $tag = $repository->tags->firstWhere('name', $this->argument('ref'));

        if ($tag) {
            $this->dump($tag->cloverFile());
            return self::SUCCESS;
        }

        /** @var Commits $commit */
        $commit = $repository->commits->firstWhere('sha1', $this->argument('ref'));

        if ($commit) {
            $this->dump($commit->cloverFile());
            return self::SUCCESS;
        }

        return self::FAILURE;
    }

    private function dump(string $cloverFile)
    {
        $xml = simplexml_load_file($cloverFile);

        foreach ($xml->project->metrics as $metrics) {
            dump((array)$metrics);
        }
    }
}
