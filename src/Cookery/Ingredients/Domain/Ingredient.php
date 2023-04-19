<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Domain;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity()]
class Ingredient implements IngredientInterface, AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_string', unique: true)]
    private UuidInterface $id;

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    private function __construct(
        ?UuidInterface $id,
        ?string $name,
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public static function new(
        ?UuidInterface $id,
        ?string $name,
    ): Ingredient {
        if (!$name || strlen($name) < 2) {
            throw new InvalidArgumentException('Ingredient should have at least 2 characters');
        }

        return new Ingredient($id, strtolower($name));
    }

    public static function fromIngredient(IngredientInterface $ingredient): Ingredient
    {
        return new Ingredient(Uuid::random(), $ingredient->name());
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
