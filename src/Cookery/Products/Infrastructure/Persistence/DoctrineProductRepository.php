<?php

declare(strict_types=1);

namespace App\Cookery\Products\Infrastructure\Persistence;

use App\Cookery\Products\Domain\Product;
use App\Cookery\Products\Domain\ProductCollection;
use App\Cookery\Products\Domain\ProductRepository;
use App\Shared\Infrastructure\Persistence\DoctrineRepository;
use Doctrine\Common\Collections\Criteria;

final class DoctrineProductRepository extends DoctrineRepository implements ProductRepository
{
    public function all(): ProductCollection
    {
        return new ProductCollection($this->repository(Product::class)->findAll());
    }

    public function mainProducts(): ProductCollection
    {
        return new ProductCollection(
            $this->repository(Product::class)->findBy(['parent' => null])
        );
    }

    public function matching(Criteria $criteria): ProductCollection
    {
        return new ProductCollection(
            $this->repository(Product::class)->matching($criteria)->toArray()
        );
    }

    public function save(Product $product): void
    {
        $this->persist($product);
    }
}
