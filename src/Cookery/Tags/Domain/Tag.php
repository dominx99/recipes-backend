<?php

declare(strict_types=1);

namespace App\Cookery\Tags\Domain;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\MaxDepth;

#[ORM\Entity()]
final class Tag
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Embedded(class: TagName::class, columnPrefix: false)]
    private TagName $name;

    #[ORM\OneToMany(targetEntity: Tag::class, mappedBy: 'parent', cascade: ['persist'], fetch: 'EAGER')]
    #[MaxDepth(2)]
    private Collection $synonyms;

    #[ORM\ManyToOne(targetEntity: Tag::class, inversedBy: 'synonyms')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    private Tag $parent;

    private function __construct(string $id, TagName $name, ?Collection $synonyms = null)
    {
        $this->id = $id;
        $this->name = $name;
        if (!$synonyms) {
            $synonyms = new TagCollection();
        }
        $this->synonyms = $synonyms;

        foreach ($this->synonyms() as $synonym) {
            $synonym->setParent($this);
        }
    }

    public static function new(string $id, TagName $name, ?Collection $synonyms = null): Tag
    {
        return new Tag($id, $name, $synonyms);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): TagName
    {
        return $this->name;
    }

    public function synonyms(): TagCollection
    {
        return $this->synonyms;
    }

    public function parent(): Tag
    {
        return $this->parent;
    }

    public function setParent(Tag $parent): void
    {
        $this->parent = $parent;
    }
}
