<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class Git extends Command
{
    protected $signature = 'app:git';

    protected $description = 'Command description';

    public function handle(): int
    {
        $result = Process::run('git -v')
            ->output();

        $this->line($result);

        return self::SUCCESS;
    }
}
