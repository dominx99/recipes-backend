<?php

declare(strict_types=1);

namespace App\Cookery\Tags\Infrastructure\Persistence;

use App\Cookery\Tags\Domain\Tag;
use App\Cookery\Tags\Domain\TagCollection;
use App\Cookery\Tags\Domain\TagRepository;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;
use Doctrine\Common\Collections\Criteria;

final class DoctrineTagRepository extends DoctrineRepository implements TagRepository
{
    public function mainTags(): TagCollection
    {
        return new TagCollection(
            $this->repository(Tag::class)->findBy(['parent' => null])
        );
    }

    public function matching(Criteria $criteria): TagCollection
    {
        return new TagCollection(
            $this->repository(Tag::class)->matching($criteria)->toArray()
        );
    }
}