<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Domain;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
final class Ingredient implements IngredientInterface, AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    private function __construct(
        ?string $id,
        ?string $name,
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public static function new(
        ?string $id,
        ?string $name,
    ): Ingredient {
        return new Ingredient($id, $name);
    }

    public static function fromIngredient(IngredientInterface $ingredient): Ingredient
    {
        return new Ingredient((string) Uuid::random(), $ingredient->name());
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
