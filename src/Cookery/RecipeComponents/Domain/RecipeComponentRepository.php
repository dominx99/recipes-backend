<?php

declare(strict_types=1);

namespace App\Cookery\RecipeComponents\Domain;

use App\Cookery\Recipes\Domain\RecipeComponent;
use Ramsey\Uuid\UuidInterface;

interface RecipeComponentRepository
{
    public function save(RecipeComponent $component): void;

    public function remove(RecipeComponent $component, bool $flush = true): void;

    public function findOne(UuidInterface $id): ?RecipeComponent;
}
