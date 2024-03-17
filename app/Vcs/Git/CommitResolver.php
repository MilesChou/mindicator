<?php

namespace App\Vcs\Git;

use Composer\Repository\Vcs\VcsDriverInterface;
use Throwable;

class CommitResolver
{
    public function __construct(private VcsDriverInterface $driver)
    {
    }

    /**
     * Ref seq
     *
     * .git/<ref>
     * .git/refs/<ref>
     * .git/refs/tags/<ref, tag_name>
     * .git/refs/heads/<ref, local_branch>
     * .git/refs/remotes/<ref>
     * .git/refs/remotes/<ref, remote_branch>/HEAD
     */
    public function resolve(string $ref): string
    {
        if ($commit = $this->searchInTags($ref)) {
            return $commit;
        } elseif ($commit = $this->searchInBranches($ref)) {
            return $commit;
        }

        try {
            $this->driver->getChangeDate($ref);
        } catch (Throwable $e) {
            // Throw exception when commit not found.
            throw $e;
        }

        return $ref;
    }

    private function searchInTags(string $ref): ?string
    {
        $tags = $this->driver->getTags();

        return $tags[$ref] ?? null;
    }

    private function searchInBranches(string $ref): ?string
    {
        $branches = $this->driver->getBranches();

        return $branches[$ref] ?? null;
    }
}
