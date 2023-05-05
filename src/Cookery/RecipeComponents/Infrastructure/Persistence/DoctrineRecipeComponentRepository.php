<?php

declare(strict_types=1);

namespace App\Cookery\RecipeComponents\Infrastructure\Persistence;

use App\Cookery\RecipeComponents\Domain\RecipeComponentRepository;
use App\Cookery\Recipes\Domain\RecipeComponent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

final class DoctrineRecipeComponentRepository extends ServiceEntityRepository implements RecipeComponentRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, RecipeComponent::class);
    }

    public function save(RecipeComponent $component, bool $flush = true): void
    {
        $this->_em->persist($component);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(RecipeComponent $component, bool $flush = true): void
    {
        $this->getEntityManager()->remove($component);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOne(UuidInterface $id): ?RecipeComponent
    {
        return $this->find($id);
    }
}
