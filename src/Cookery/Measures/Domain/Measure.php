<?php

declare(strict_types=1);

namespace App\Cookery\Measures\Domain;

use App\Shared\Domain\Enum\Unit;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable()]
final class Measure
{
    #[ORM\Column(name: 'unit', enumType: 'string', nullable: true)]
    private ?Unit $unit;

    #[ORM\Column(name: 'numeric_quantity', type: 'string', nullable: true)]
    private ?string $numericQuantity;

    #[ORM\Column(name: 'formatted_quantity', type: 'string', nullable: true)]
    private ?string $formattedQuantity;

    public function __construct(?Unit $unit, ?string $numericQuantity, ?string $formattedQuantity)
    {
        $this->unit = $unit;
        $this->numericQuantity = $numericQuantity;
        $this->formattedQuantity = $formattedQuantity;
    }

    public function unit(): Unit
    {
        return $this->unit;
    }

    public function numericQuantity(): ?string
    {
        return $this->numericQuantity;
    }

    public function formattedQuantity(): ?string
    {
        return $this->formattedQuantity;
    }
}
