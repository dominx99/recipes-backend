<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Domain;

use App\Cookery\Ingredients\Domain\Ingredient;
use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Cookery\Measures\Domain\Measure;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity()]
class RecipeComponent implements RecipeComponentInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_string', unique: true)]
    #[JMS\Type(name: 'string')]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: Recipe::class, inversedBy: 'components')]
    #[ORM\JoinColumn(name: 'recipe_id', referencedColumnName: 'id', nullable: false)]
    private RecipeInterface $recipe;

    #[ORM\ManyToOne(targetEntity: Ingredient::class)]
    #[ORM\JoinColumn(name: 'ingredient_id', referencedColumnName: 'id', nullable: false, unique: false)]
    private IngredientInterface $ingredient;

    #[ORM\Embedded(class: Measure::class, columnPrefix: false)]
    private ?Measure $measure;

    private function __construct(UuidInterface $id, IngredientInterface $ingredient, ?Measure $measure)
    {
        $this->id = $id;
        $this->ingredient = $ingredient;
        $this->measure = $measure;
    }

    public static function new(UuidInterface $id, IngredientInterface $ingredient, ?Measure $measure): RecipeComponent
    {
        return new RecipeComponent($id, $ingredient, $measure);
    }

    public static function fromComponent(RecipeComponentInterface $component): RecipeComponentInterface
    {
        return RecipeComponent::new(
            Uuid::random(),
            $component->ingredient(),
            $component->measure(),
        );
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function ingredient(): IngredientInterface
    {
        return $this->ingredient;
    }

    public function measure(): ?Measure
    {
        return $this->measure;
    }

    public function setRecipe(Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }
}
