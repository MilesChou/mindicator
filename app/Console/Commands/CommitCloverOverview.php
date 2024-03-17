<?php

namespace App\Console\Commands;

use App\Models\Vcs\Commits;
use Illuminate\Console\Command;

class CommitCloverOverview extends Command
{
    protected $signature = 'app:commit:clover:overview
                                {commit}';

    protected $description = 'Show the commit overview';

    public function handle(): int
    {
        /** @var Commits $commit */
        $commit = Commits::query()
            ->where('sha1', $this->argument('commit'))
            ->firstOrFail();

        $commit->cloverFile();

        $xml = simplexml_load_file($commit->cloverFile());

        foreach ($xml->project->metrics as $metrics) {
            dump((array) $metrics);
        }

        return self::SUCCESS;
    }
}
