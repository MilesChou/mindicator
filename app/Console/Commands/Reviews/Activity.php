<?php

namespace App\Console\Commands\Reviews;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Activity extends Command
{
    protected $signature = 'reviews:activity {repo} {number} {--token=}';

    protected $description = 'Get activity for repo';

    public function handle(): int
    {
        $baseUrl = 'https://api.github.com/';

        $response = Http::withToken($this->option('token'))
            ->get($baseUrl . "repos/{$this->argument('repo')}/pulls/{$this->argument('number')}/reviews");

        $response->collect()
            ->map(function (array $data) {
                return "{$data['user']['login']} {$data['state']} at {$data['submitted_at']}";
            })
            ->dd();

        return self::SUCCESS;
    }
}
