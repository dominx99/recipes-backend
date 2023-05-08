<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Infrastructure\Persistence;

use App\Cookery\Publishing\Domain\PublishRecipeRequest;
use App\Cookery\Publishing\Domain\PublishRecipeRequestRepository;
use App\Shared\Domain\Collection\ArrayCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

final class DoctrinePublishRecipeRequestRepository extends ServiceEntityRepository implements PublishRecipeRequestRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PublishRecipeRequest::class);
    }

    public function findOne(UuidInterface $id): ?PublishRecipeRequest
    {
        return $this->find($id);
    }

    public function save(PublishRecipeRequest $publishRecipeRequest, bool $flush = true): void
    {
        $this->getEntityManager()->persist($publishRecipeRequest);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PublishRecipeRequest $publishRecipeRequest, bool $flush = true): void
    {
        $this->getEntityManager()->remove($publishRecipeRequest);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function all(): ArrayCollection
    {
        return new ArrayCollection($this->findAll());
    }
}
