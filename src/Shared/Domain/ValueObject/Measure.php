<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Enum\Unit;

class Measure
{
    public function __construct(
        protected ?Unit $unit,
        protected ?string $numericQuantity,
        protected ?string $formattedQuantity,
    ) {
    }

    public function unit(): Unit
    {
        return $this->unit;
    }

    public function numericQuantity(): string
    {
        return $this->numericQuantity;
    }

    public function formattedQuantity(): string
    {
        return $this->formattedQuantity;
    }
}
