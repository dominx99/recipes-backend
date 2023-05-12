<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Application\Update;

final class AcceptPublishRecipeRequestCommand
{
    public function __construct(public readonly string $recipeId)
    {
    }
}
