<?php

namespace App\Console\Commands;

use App\Models\Vcs\Repositories;
use App\Vcs\Git\Factory as GitFactory;
use App\Vcs\Git\ReferenceInterface;
use App\Vcs\VcsInterface;
use Composer\Repository\Vcs\VcsDriverInterface;
use Composer\Util\Platform;
use DOMDocument;
use Illuminate\Console\Command;
use Symfony\Component\Console\Terminal;
use Throwable;

class CloverSource extends Command
{
    protected $signature = 'app:clover:source
                                {url}
                                {ref}
                                {path}
                                {--disable-network : Disable network, just use cache or fail}
                                ';

    protected $description = 'Show the clover file coverage';

    public function handle(GitFactory $gitFactory): int
    {
        if ($this->option('disable-network')) {
            Platform::putEnv('COMPOSER_DISABLE_NETWORK', true);
        }

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
            $refEntity = $repository->findRef($ref);
        } catch (Throwable $e) {
            $this->error('Cannot find ref: ' . $ref);
            $this->error('Error: ' . $e->getMessage());

            return self::FAILURE;
        }

        $vcs = $gitFactory->create($url);

        $basePath = $this->resolveBasePathInCloverXml($vcs, $refEntity);

        $xml = simplexml_load_file($refEntity->cloverFile());

        foreach ($xml->xpath('//file') as $file) {
            $filename = explode($basePath . '/', $file->attributes()['name'], 2)[1];

            if ($filename === $this->argument('path')) {
                break;
            }
        }

        dump($file->class->metrics);

        $fileContent = $vcs->getFileContent($this->argument('path'), $refEntity->ref());
        $fileContent = array_map(fn($line) => [$line, 0], explode("\n", $fileContent));

        foreach ($file->line as $line) {
            $attr = $line->attributes();

            $fileContent[(int)$attr['num'] - 1] = [
                $fileContent[(int)$attr['num'] - 1][0],
                (int)$attr['count'],
            ];
        }

        foreach ($fileContent as $key => $line) {
            $lineString = mb_str_pad($line[0], (new Terminal())->getWidth());

            if ($line[1] > 0) {
                $this->line($lineString, 'bg=green');
            } else {
                $this->line($lineString, 'bg=red');
            }
        }

        return self::SUCCESS;
    }

    private function resolveBasePathInCloverXml(VcsInterface $vcs, ReferenceInterface $ref): string
    {
        $xml = simplexml_load_file($ref->cloverFile());

        $pathTemp = (string)$xml->xpath('//file[1]')[0]->attributes()['name'];
        $pathTemp = explode('/', ltrim(dirname($pathTemp), '/'));

        $includePattern = [];
        $excludePattern = [];

        while (!empty($pathTemp)) {
            $test = array_shift($pathTemp);

            $testpath = implode('/', [
                ...$includePattern,
                $test,
            ]);

            $content = $vcs->getFileContent($testpath, $ref->ref());

            $check = 'tree ' . $ref->ref() . ':' . $testpath;

            if (null === $content || !str_starts_with($content, $check)) {
                $excludePattern[] = $test;
                continue;
            }

            $includePattern[] = $test;
        }

        return implode('/', $excludePattern);
    }
}
