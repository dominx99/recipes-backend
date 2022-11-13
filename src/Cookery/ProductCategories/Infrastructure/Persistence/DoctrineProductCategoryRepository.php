<?php

declare(strict_types=1);

namespace App\Cookery\ProductCategories\Infrastructure\Persistence;

use App\Cookery\ProductCategories\Domain\ProductCategory;
use App\Cookery\ProductCategories\Domain\ProductCategoryCollection;
use App\Cookery\ProductCategories\Domain\ProductCategoryRepository;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;

final class DoctrineProductCategoryRepository extends DoctrineRepository implements ProductCategoryRepository
{
    public function all(): ProductCategoryCollection
    {
        return new ProductCategoryCollection($this->repository(ProductCategory::class)->findAll());
    }
}
