<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Infrastructure\Persistence;

use App\Cookery\Recipes\Domain\MatchingRecipeCollection;
use App\Cookery\Recipes\Domain\Recipe;
use App\Cookery\Recipes\Domain\RecipeCollection;
use App\Cookery\Recipes\Domain\RecipeRepository;
use App\Cookery\Recipes\Domain\ValueObject\MatchingRecipe;
use App\Shared\Domain\AggregateRoot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

final class DoctrineRecipeRepository extends ServiceEntityRepository implements RecipeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function all(): RecipeCollection
    {
        return new RecipeCollection(
            $this->createQueryBuilder('r')
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

    /**
     * @param array<int,string> $ingredients
     */
    public function matchByIngredients(array $ingredients): MatchingRecipeCollection
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Recipe::class, 'r');
        $rsm->addScalarResult('matchingRecipeCount', 'matchingRecipeCount');

        $query = $this
            ->getEntityManager()
            ->getConnection()
            ->createQueryBuilder()
            ->select('r.*, count(rc.id) as matchingRecipeCount')
            ->from('recipe_component', 'rc')
            ->join('rc', 'recipe', 'r', 'r.id = rc.recipe_id')
            ->where('rc.ingredient_id IN (
                SELECT i.id FROM ingredient i
                WHERE i.name REGEXP :ingredients
            )')
            ->groupBy('rc.recipe_id')
            ->orderBy('(count(rc.id) / r.componentsCount)', 'DESC')
        ;

        $recipes = $this->getEntityManager()
            ->createNativeQuery($query->getSQL(), $rsm)
            ->setParameter('ingredients', implode('|', $ingredients))
            ->execute()
        ;

        return new MatchingRecipeCollection(array_map(
            fn (array $row) => new MatchingRecipe($row[0], $row['matchingRecipeCount']),
            $recipes
        ));
    }
}
