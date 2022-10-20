<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Domain;

use App\Shared\Domain\Enum\Unit;
use App\Shared\Domain\ValueObject\Measure;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Embeddable()]
final class IngredientMeasure extends Measure
{
    #[Column(name: 'unit', enumType: Unit::class, nullable: true)]
    protected ?Unit $unit;

    #[Column(name: 'numericQuantity', type: 'string', nullable: true)]
    protected ?string $numericQuantity;

    #[Column(name: 'formattedQuantity', type: 'string', nullable: true)]
    protected ?string $formattedQuantity;
}
