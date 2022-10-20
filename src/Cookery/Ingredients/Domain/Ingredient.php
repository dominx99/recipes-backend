<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Domain;

use App\Shared\Domain\ValueObject\Measure;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
final class Ingredient
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    #[ORM\Embedded(class: IngredientMeasure::class, columnPrefix: false)]
    private ?IngredientMeasure $measure;

    private function __construct(
        ?string $id,
        ?string $name,
        ?IngredientMeasure $measure,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->measure = $measure;
    }

    public static function new(
        ?string $id,
        ?string $name,
        ?IngredientMeasure $measure
    ): Ingredient {
        return new Ingredient($id, $name, $measure);
    }

    public static function fromIngredient(IngredientInterface $ingredient): Ingredient {
        return new Ingredient((string) Uuid::random(), $ingredient->name(), $ingredient->measure());
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function measure(): IngredientMeasure
    {
        return $this->measure;
    }
}
