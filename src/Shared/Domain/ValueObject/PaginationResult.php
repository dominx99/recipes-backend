<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class PaginationResult
{
    private function __construct(
        private array $data,
        private ?string $nextPageUrl,
    ) {
    }

    public static function empty(): self
    {
        return new self([], null);
    }

    public static function new(array $data, ?string $nextPageUrl): self
    {
        return new self($data, $nextPageUrl);
    }

    public function data(): array
    {
        return $this->data;
    }

    public function nextPageUrl(): ?string
    {
        return $this->nextPageUrl;
    }
}
