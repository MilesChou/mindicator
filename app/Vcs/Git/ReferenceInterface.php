<?php

namespace App\Vcs\Git;

/**
 * Implement on git object
 */
interface ReferenceInterface
{
    public function ref(): string;
}
