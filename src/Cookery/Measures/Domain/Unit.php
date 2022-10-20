<?php

declare(strict_types=1);

namespace App\Cookery\Measures\Domain;

use App\Shared\Domain\Enum\Unit as UnitEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
final class Unit
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Column(name: 'name', enumType: UnitEnum::class)]
    private string $name;

    public function __construct(string $id, UnitEnum $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
