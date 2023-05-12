<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Domain;

use App\Shared\Domain\Collection\ArrayCollection;
use Doctrine\Persistence\ObjectRepository;
use Ramsey\Uuid\UuidInterface;

/**
 * @extends ObjectRepository<PublishRecipeRequest>
 */
interface PublishRecipeRequestRepository extends ObjectRepository
{
    public function all(): ArrayCollection;

    public function allPaginated(int $offset = 0, int $limit = 24): ArrayCollection;

    public function findOne(UuidInterface $id): ?PublishRecipeRequest;

    public function save(PublishRecipeRequest $publishRecipeRequest, bool $flush = true): void;

    public function remove(PublishRecipeRequest $publishRecipeRequest, bool $flush = true): void;
}
