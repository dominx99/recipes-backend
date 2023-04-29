<?php

declare(strict_types=1);

namespace App\Cookery\Ingredients\Application\Create;

use Ramsey\Uuid\UuidInterface;

final class CreateIngredientCommand
{
    public function __construct(
        public readonly UuidInterface $id,
        public readonly string $name,
    ) {
    }
}
