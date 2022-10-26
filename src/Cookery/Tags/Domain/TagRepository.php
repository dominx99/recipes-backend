<?php

declare(strict_types=1);

namespace App\Cookery\Tags\Domain;

interface TagRepository
{
    public function mainTags(): TagCollection;
}
