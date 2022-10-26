<?php

declare(strict_types=1);

namespace App\Cookery\Tags\Domain;

use App\Shared\Domain\ValueObject\StringValueObject;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable()]
final class TagName extends StringValueObject
{
    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    protected string $value;
}
