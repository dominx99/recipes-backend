<?php

declare(strict_types=1);

namespace App\Shared\Application\Compare;

final class StringContains
{
    public function __construct(private string $haystack, private string $needle)
    {
    }

    public function __invoke(): bool
    {
        return str_contains($this->haystack, $this->needle);
    }
}
