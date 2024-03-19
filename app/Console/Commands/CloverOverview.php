<?php

namespace App\Console\Commands;

use App\Models\Vcs\Repositories;
use Illuminate\Console\Command;
use Throwable;

class CloverOverview extends Command
{
    protected $signature = 'app:clover:overview
                                {url}
                                {ref}';

    protected $description = 'Show the commit overview';

    public function handle(): int
    {
        $url = $this->argument('url');

        try {
            /** @var Repositories $repository */
            $repository = Repositories::query()
                ->where('url', $url)
                ->firstOrFail();
        } catch (Throwable $e) {
            $this->error('Cannot find repository: ' . $url);
            $this->error('Error: ' . $e->getMessage());

            return self::FAILURE;
        }

        $ref = $this->argument('ref');

        try {
            $ref = $repository->findRef($ref);
        } catch (Throwable $e) {
            $this->error('Cannot find ref: ' . $ref);
            $this->error('Error: ' . $e->getMessage());

            return self::FAILURE;
        }

        $this->dump($ref->cloverFile());

        return self::SUCCESS;
    }

    private function dump(string $cloverFile)
    {
        $xml = simplexml_load_file($cloverFile);

        foreach ($xml->project->metrics as $metrics) {
            dump((array)$metrics);
        }
    }
}
