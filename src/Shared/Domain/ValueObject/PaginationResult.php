<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class PaginationResult
{
    private function __construct(
        private array $data,
        private bool $isFirstPage,
        private ?string $nextPageUrl,
    ) {
    }

    public static function empty(): self
    {
        return new self([], false, null);
    }

    public static function new(
        array $data,
        bool $isFirstPage,
        ?string $nextPageUrl
    ): self {
        return new self($data, $isFirstPage, $nextPageUrl);
    }

    public function data(): array
    {
        return $this->data;
    }

    public function getIsFirstPage(): bool
    {
        return $this->isFirstPage;
    }

    public function nextPageUrl(): ?string
    {
        return $this->nextPageUrl;
    }
}
