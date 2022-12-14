<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence;

use App\Shared\Domain\AggregateRoot;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class DoctrineRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    protected function entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function persist(AggregateRoot $entity, bool $flush = true): void
    {
        $this->entityManager()->persist($entity);

        if ($flush) {
            $this->entityManager()->flush();
        }
    }

    protected function repository(string $entityClass): EntityRepository
    {
        return $this->entityManager->getRepository($entityClass);
    }
}
