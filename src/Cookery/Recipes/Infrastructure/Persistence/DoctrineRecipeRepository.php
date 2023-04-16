<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Infrastructure\Persistence;

use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Shared\Domain\AggregateRoot;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineRecipeRepository extends ServiceEntityRepository implements RecipeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function all(): RecipeCollection
    {
        return new RecipeCollection($this->createQueryBuilder('r')
            ->getQuery()
            ->enableResultCache(900)
            ->setCacheable(true)
            ->getResult()
        );
    }

    public function save(AggregateRoot $recipe): void
    {
        $this->getEntityManager()->persist($recipe);
    }

    public function matching(Criteria $criteria): RecipeCollection
    {
        return new RecipeCollection($this->matching($criteria)->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function findMany(array $ids): RecipeCollection
    {
        return new RecipeCollection($this->findBy(['id' => $ids]));
    }
}
