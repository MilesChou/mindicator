<?php

namespace App\Vcs;

use Composer\Repository\Vcs\VcsDriverInterface;
use DateTimeImmutable;

/**
 * @deprecated
 */
readonly class ComposerDriverWrapper implements VcsInterface
{
    public static function wrap(VcsDriverInterface $composerDriver): VcsInterface
    {
        return new self($composerDriver);
    }

    public function __construct(private VcsDriverInterface $composerDriver)
    {
    }

    public function initialize(): void
    {
        $this->composerDriver->initialize();
    }

    public function getFileContent(string $file, string $identifier): ?string
    {
        return $this->composerDriver->getFileContent($file, $identifier);
    }

    public function getChangeDate(string $identifier): ?DateTimeImmutable
    {
        return $this->composerDriver->getChangeDate($identifier);
    }

    public function getRootIdentifier(): string
    {
        return $this->composerDriver->getRootIdentifier();
    }

    public function getBranches(): array
    {
        return $this->composerDriver->getBranches();
    }

    public function getTags(): array
    {
        return $this->composerDriver->getTags();
    }

    public function getUrl(): string
    {
        return $this->composerDriver->getUrl();
    }
}
