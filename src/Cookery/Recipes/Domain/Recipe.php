<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity()]
#[ORM\Table(name: 'recipe')]
#[ORM\Cache('NONSTRICT_READ_WRITE')]
class Recipe implements RecipeInterface, AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_string', unique: true)]
    #[JMS\Type(name: 'string')]
    private UuidInterface $id;

    #[ORM\Column(name: 'externalIdentifier', type: 'string')]
    private string $externalIdentifier;

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    #[OneToMany(targetEntity: RecipeComponent::class, mappedBy: 'recipe', cascade: ['persist', 'remove'], fetch: 'EAGER')]
    private Collection $components;

    #[ORM\Column(name: 'componentsCount', type: 'integer')]
    private int $componentsCount;

    private function __construct(
        UuidInterface $id,
        string $externalIdentifier,
        string $name,
        RecipeComponentCollection $components,
        int $componentsCount
    ) {
        $this->id = $id;
        $this->externalIdentifier = $externalIdentifier;
        $this->name = $name;
        $this->components = $components;
        $this->componentsCount = $componentsCount;
    }

    /**
     * @param Collection<array-key,RecipeComponent> $components
     */
    public static function new(
        UuidInterface $id,
        string $externalIdentifier,
        string $name,
        Collection $components
    ): RecipeInterface {
        $recipe = new Recipe(
            $id,
            $externalIdentifier,
            $name,
            $components,
            $components->count()
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
            Uuid::random(),
            $recipe->externalIdentifier(),
            $recipe->name(),
            $recipe->components()->map(
                fn (RecipeComponentInterface $component) => RecipeComponent::fromComponent($component),
            )
        );
    }

    public function id(): UuidInterface
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
        if ($this->components instanceof RecipeComponentCollection) {
            return $this->components;
        }

        $this->components = new RecipeComponentCollection($this->components->toArray());

        return $this->components;
    }

    public function componentsCount(): int
    {
        return $this->componentsCount;
    }
}
