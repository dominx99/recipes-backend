<?php

declare(strict_types=1);

namespace App\Cookery\Products\Domain;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\MaxDepth;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity()]
class Product implements AggregateRoot
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_string', unique: true)]
    #[JMS\Type(name: 'string')]
    private UuidInterface $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    private string $name;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'parent', cascade: ['persist'], fetch: 'EAGER')]
    #[MaxDepth(2)]
    private Collection $synonyms;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'synonyms')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    private Product $parent;

    private function __construct(UuidInterface $id, string $name, ?Collection $synonyms = null)
    {
        $this->id = $id;
        $this->name = $name;
        if (!$synonyms) {
            $synonyms = new ProductCollection();
        }
        $this->synonyms = $synonyms;

        foreach ($this->synonyms() as $synonym) {
            $synonym->setParent($this);
        }
    }

    public static function new(UuidInterface $id, string $name, ?Collection $synonyms = null): Product
    {
        return new Product($id, $name, $synonyms);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function synonyms(): Collection
    {
        return $this->synonyms;
    }

    public function parent(): Product
    {
        return $this->parent;
    }

    public function setParent(Product $parent): void
    {
        $this->parent = $parent;
    }
}
