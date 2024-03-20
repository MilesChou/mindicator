<?php

namespace App\Vcs;

use DateTimeImmutable;

interface VcsInterface
{
    /**
     * Initializes the driver (git clone, svn checkout, fetch info etc)
     */
    public function initialize(): void;

    /**
     * Return the content of $file or null if the file does not exist.
     */
    public function getFileContent(string $file, string $identifier): ?string;

    /**
     * Get the changedate for $identifier.
     */
    public function getChangeDate(string $identifier): ?DateTimeImmutable;

    /**
     * Return the root identifier (trunk, master, default/tip ..)
     *
     * @return string Identifier
     */
    public function getRootIdentifier(): string;

    /**
     * Return list of branches in the repository
     *
     * @return array<int|string, string> Branch names as keys, identifiers as values
     */
    public function getBranches(): array;

    /**
     * Return list of tags in the repository
     *
     * @return array<int|string, string> Tag names as keys, identifiers as values
     */
    public function getTags(): array;

    /**
     * Return the URL of the repository
     */
    public function getUrl(): string;
}
