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
class Recipe implements RecipeInterface, AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_string', unique: true)]
    #[JMS\Type(name: 'string')]
    private UuidInterface $id;

    #[ORM\Column(type: 'uuid_string', nullable: true)]
    #[JMS\Type(name: 'string')]
    private ?UuidInterface $ownerId = null;

    #[ORM\Column(name: 'externalIdentifier', type: 'string', nullable: true)]
    private ?string $externalIdentifier = null;

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    #[OneToMany(targetEntity: RecipeComponent::class, mappedBy: 'recipe', cascade: ['persist', 'remove'], fetch: 'EAGER')]
    private Collection $components;

    #[ORM\Column(name: 'componentsCount', type: 'integer')]
    private int $componentsCount;

    #[ORM\Column(name: 'published', type: 'boolean', nullable: false, options: ['default' => false])]
    private bool $published = false;

    private function __construct(
        UuidInterface $id,
        string $name,
        RecipeComponentCollection $components,
        int $componentsCount,
        ?string $externalIdentifier = null,
        ?UuidInterface $ownerId = null,
        bool $published = false,
    ) {
        $this->id = $id;
        $this->externalIdentifier = $externalIdentifier;
        $this->name = $name;
        $this->components = $components;
        $this->componentsCount = $componentsCount;
        $this->ownerId = $ownerId;
        $this->published = $published;
    }

    /**
     * @param Collection<array-key,RecipeComponent> $components
     */
    public static function new(
        UuidInterface $id,
        string $name,
        Collection $components,
        ?string $externalIdentifier = null,
        ?UuidInterface $ownerId = null,
        bool $published = false,
    ): RecipeInterface {
        $recipe = new Recipe(
            $id,
            $name,
            $components,
            $components->count(),
            $externalIdentifier,
            $ownerId,
            $published
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
            $recipe->name(),
            $recipe->components()->map(
                fn (RecipeComponentInterface $component) => RecipeComponent::fromComponent($component),
            ),
            $recipe->externalIdentifier(),
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

    /** @var RecipeComponentCollection<int, RecipeComponent> */
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

    public function setComponentsCount(int $componentsCount): void
    {
        $this->componentsCount = $componentsCount;
    }

    public function ownerId(): UuidInterface
    {
        return $this->ownerId;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function update(
        string $name,
        RecipeComponentCollection $components,
    ): void {
        $this->name = $name;
        $this->components = $components;
    }
}
