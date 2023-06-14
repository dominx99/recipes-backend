<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Domain;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class PublishRecipeRequest
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid_string')]
        #[JMS\Type(name: 'string')]
        public readonly UuidInterface $id,
        #[ORM\Column(type: 'uuid_string', nullable: true)]
        #[JMS\Type(name: 'string')]
        public readonly ?UuidInterface $ownerId = null,
        #[ORM\Column(type: 'uuid_string', nullable: true)]
        #[JMS\Type(name: 'string')]
        public readonly ?UuidInterface $recipeId = null,
    ) {
    }
}
