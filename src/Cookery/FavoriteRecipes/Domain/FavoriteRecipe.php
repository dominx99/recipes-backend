<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Domain;

use App\Shared\Domain\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
final class FavoriteRecipe implements AggregateRoot
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private string $id,
        #[ORM\Column(type: 'uuid')]
        private string $userId,
        #[ORM\Column(type: 'uuid')]
        private string $recipeId,
    ) {
    }
}
