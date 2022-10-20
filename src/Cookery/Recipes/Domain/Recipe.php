<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain;

use App\Cookery\Ingredients\Domain\Ingredient;
use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Ingredients\IngredientCollection;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity()]
class Recipe implements RecipeInterface, AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    #[OneToMany(targetEntity: Ingredient::class, mappedBy: 'recipe', cascade: ['persist'])]
    private Collection $ingredients;

    private function __construct(
        string $id,
        string $name,
        IngredientCollection $ingredients
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->ingredients = $ingredients;
    }

    public static function new(
        string $id,
        string $name,
        IngredientCollection $ingredients
    ): RecipeInterface {
        return new Recipe(
            $id,
            $name,
            $ingredients
        );
    }

    public static function fromRecipe(RecipeInterface $recipe): RecipeInterface
    {
        return self::new(
            (string) Uuid::random(),
            $recipe->name(),
            $recipe->ingredients()->map(
                fn (IngredientInterface $ingredient) => Ingredient::fromIngredient($ingredient),
            )
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function ingredients(): IngredientCollection
    {
        return $this->ingredients;
    }
}
