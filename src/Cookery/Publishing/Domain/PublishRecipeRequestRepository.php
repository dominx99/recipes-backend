<?php

declare(strict_types=1);

namespace App\Cookery\Publishing\Domain;

use Ramsey\Uuid\UuidInterface;

interface PublishRecipeRequestRepository
{
    public function findOne(UuidInterface $id): ?PublishRecipeRequest;

    public function save(PublishRecipeRequest $publishRecipeRequest, bool $flush = true): void;

    public function remove(PublishRecipeRequest $publishRecipeRequest, bool $flush = true): void;
}
