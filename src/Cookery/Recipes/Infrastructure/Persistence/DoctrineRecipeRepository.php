<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Infrastructure\Persistence;

use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;

final class DoctrineRecipeRepository extends DoctrineRepository implements RecipeRepository
{
    public function save(AggregateRoot $recipe): void
    {
        $this->persist($recipe);
    }
}
