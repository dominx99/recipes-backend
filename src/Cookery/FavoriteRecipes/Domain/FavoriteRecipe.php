<?php

declare(strict_types=1);

namespace App\Cookery\FavoriteRecipes\Domain;

use App\Shared\Domain\AggregateRoot;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JMS\Serializer\Annotation as JMS;
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
        #[JMS\Type(name: 'string')]
        private UuidInterface $id,
        #[ORM\Column(type: 'uuid_string')]
        #[Exclude()]
        private UuidInterface $userId,
        #[ORM\Column(type: 'uuid_string')]
        #[JMS\Type(name: 'string')]
        private UuidInterface $recipeId,
    ) {
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function recipeId(): UuidInterface
    {
        return $this->recipeId;
    }

    public function userId(): UuidInterface
    {
        return $this->userId;
    }
}
