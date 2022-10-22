<?php

declare(strict_types=1);

namespace App\Ingredients;

use App\Cookery\Ingredients\Domain\IngredientInterface;
use App\Shared\Domain\Collection\Collection;

final class IngredientCollection extends Collection
{
    public function unique(): IngredientCollection
    {
        $names = [];

        return new IngredientCollection(array_filter($this->getValues(), function (IngredientInterface $ingredient) use (&$names) {
            if (in_array($ingredient->name(), $names)) {
                return false;
            }

            $names[] = $ingredient->name();

            return true;
        }));
    }
}
