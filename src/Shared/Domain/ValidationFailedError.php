<?php

declare(strict_types=1);

namespace App\Shared\Domain;

use InvalidArgumentException;

final class ValidationFailedError extends InvalidArgumentException
{
    public function __construct(private array $errors)
    {
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
