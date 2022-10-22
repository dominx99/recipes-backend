<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain;

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

    #[ORM\Column(name: 'externalIdentifier', type: 'string')]
    private string $externalIdentifier;

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    #[OneToMany(targetEntity: RecipeComponent::class, mappedBy: 'recipe', cascade: ['persist', 'remove'], fetch: 'EAGER')]
    private Collection $components;

    private function __construct(
        string $id,
        string $externalIdentifier,
        string $name,
        RecipeComponentCollection $components
    ) {
        $this->id = $id;
        $this->externalIdentifier = $externalIdentifier;
        $this->name = $name;
        $this->components = $components;
    }

    public static function new(
        string $id,
        string $externalIdentifier,
        string $name,
        Collection $components
    ): RecipeInterface {
        $recipe = new Recipe(
            $id,
            $externalIdentifier,
            $name,
            $components
        );

        $components->forAll(function ($key, RecipeComponent $recipeComponent) use ($recipe) {
            $recipeComponent->setRecipe($recipe);

            return true;
        });

        return $recipe;
    }

    public static function fromRecipe(RecipeInterface $recipe): RecipeInterface
    {
        return self::new(
            (string) Uuid::random(),
            $recipe->externalIdentifier(),
            $recipe->name(),
            $recipe->components()->map(
                fn (RecipeComponentInterface $component) => RecipeComponent::fromComponent($component),
            )
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function externalIdentifier(): string
    {
        return $this->externalIdentifier;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function components(): RecipeComponentCollection
    {
        return $this->components;
    }
}
