<?php

declare(strict_types=1);

namespace App\Cookery\ProductCategories\Domain;

interface ProductCategoryRepository
{
    public function all(): ProductCategoryCollection;
}
