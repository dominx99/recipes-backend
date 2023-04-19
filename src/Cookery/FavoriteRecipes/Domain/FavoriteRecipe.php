<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Domain;

use App\Shared\Domain\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JMS\Serializer\Annotation\Exclude;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity()]
#[UniqueConstraint(name: 'user_id_recipe_id_uidx', columns: ['user_id', 'recipe_id'])]
#[UniqueEntity(fields: ['userId', 'recipeId'])]
class FavoriteRecipe implements AggregateRoot
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid_string', unique: true)]
        private UuidInterface $id,
        #[ORM\Column(type: 'uuid_string')]
        #[Exclude()]
        private string $userId,
        #[ORM\Column(type: 'uuid_string')]
        private string $recipeId,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function recipeId(): string
    {
        return $this->recipeId;
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
