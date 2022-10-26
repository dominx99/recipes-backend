<?php

declare(strict_types=1);

namespace App\Shared\Domain\Collection;

use Doctrine\Common\Collections\ArrayCollection;

abstract class Collection extends ArrayCollection
{
    public function merge(Collection $collection): Collection
    {
        return new static(array_merge($this->toArray(), $collection->toArray()));
    }
}
