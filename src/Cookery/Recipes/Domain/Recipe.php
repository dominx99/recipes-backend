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

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    #[OneToMany(targetEntity: RecipeComponent::class, mappedBy: 'recipe', cascade: ['persist'])]
    private Collection $components;

    private function __construct(
        string $id,
        string $name,
        RecipeComponentCollection $components
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->components = $components;
    }

    public static function new(
        string $id,
        string $name,
        RecipeComponentCollection $components
    ): RecipeInterface {
        return new Recipe(
            $id,
            $name,
            $components
        );
    }

    public static function fromRecipe(RecipeInterface $recipe): RecipeInterface
    {
        return self::new(
            (string) Uuid::random(),
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

    public function name(): string
    {
        return $this->name;
    }

    public function components(): RecipeComponentCollection
    {
        return $this->components;
    }
}
