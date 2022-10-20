<?php

declare(strict_types=1);

namespace App\Cookery\Measures\Domain;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Embeddable()]
final class Measure
{
    #[ManyToOne(targetEntity: Unit::class)]
    private ?Unit $unit;

    #[Column(name: 'numericQuantity', type: 'string', nullable: true)]
    private ?string $numericQuantity;

    #[Column(name: 'formattedQuantity', type: 'string', nullable: true)]
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
