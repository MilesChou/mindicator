<?php

namespace App\Console\Commands;

use App\Models\Repositories;
use Illuminate\Console\Command;

class RepositoryCreate extends Command
{
    protected $signature = 'app:repository:create {--tag=*} {url}';

    protected $description = 'Create Repository';

    public function handle(Repositories $repositories): int
    {
        $url = $this->argument('url');
        $tags = $this->option('tag');

        $repositories->newQuery()->insert([
            'owner_id' => 0,
            'url' => $url,
            'private_key' => '',
            'tags' => json_encode($tags),
        ]);

        return self::SUCCESS;
    }
}
