<?php

declare(strict_types=1);

namespace App\Cookery\ProductCategories\Domain;

use App\Cookery\Products\Domain\Product;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class ProductCategory
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    private string $name;

    #[
        ORM\ManyToMany(
            targetEntity: Product::class,
            inversedBy: 'categories',
            cascade: ['persist'],
            fetch: 'EAGER',
        )
    ]
    private Collection $products;

    public function __construct(string $id, string $name, Collection $products)
    {
        $this->id = $id;
        $this->name = $name;
        $this->products = $products;
    }

    public static function new(string $id, string $name, Collection $products): self
    {
        return new self($id, $name, $products);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function products(): Collection
    {
        return $this->products;
    }
}
