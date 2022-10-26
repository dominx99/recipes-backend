<?php

declare(strict_types=1);

namespace App\Cookery\Tags\Domain;

use Doctrine\Common\Collections\Criteria;

interface TagRepository
{
    public function mainTags(): TagCollection;

    public function matching(Criteria $criteria): TagCollection;
}
