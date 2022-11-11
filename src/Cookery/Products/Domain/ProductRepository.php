<?php

declare(strict_types=1);

namespace App\Cookery\Products\Domain;

use Doctrine\Common\Collections\Criteria;

interface ProductRepository
{
    public function all(): ProductCollection;

    public function mainProducts(): ProductCollection;

    public function matching(Criteria $criteria): ProductCollection;

    public function save(Product $product): void;
}
