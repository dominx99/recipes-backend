<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Application\Create;

use Ramsey\Uuid\UuidInterface;

final class CreateRecipeCommand
{
    public function __construct(
        public readonly UuidInterface $id,
        public readonly string $name,
        public readonly ?UuidInterface $ownerId,
    ) {
    }
}
