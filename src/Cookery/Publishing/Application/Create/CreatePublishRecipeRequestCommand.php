<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Application\Create;

use Ramsey\Uuid\UuidInterface;

final class CreatePublishRecipeRequestCommand
{
    public function __construct(
        public readonly UuidInterface $id,
        public readonly UuidInterface $ownerId,
        public readonly UuidInterface $recipeId,
    ) {
    }
}
