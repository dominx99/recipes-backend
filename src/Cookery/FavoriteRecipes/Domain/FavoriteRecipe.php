<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Domain;

use App\Shared\Domain\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JMS\Serializer\Annotation\Exclude;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity()]
#[UniqueConstraint(name: 'user_id_recipe_id_uidx', columns: ['user_id', 'recipe_id'])]
#[UniqueEntity(fields: ['userId', 'recipeId'])]
class FavoriteRecipe implements AggregateRoot
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true)]
        private string $id,
        #[ORM\Column(type: 'uuid')]
        #[Exclude()]
        private string $userId,
        #[ORM\Column(type: 'uuid')]
        private string $recipeId,
    ) {
    }
}
