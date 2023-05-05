<?php

declare(strict_types=1);

namespace App\Cookery\Recipes\Http\Request;

use App\Shared\Domain\Enum\Unit;
use App\Shared\Http\Symfony\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;

final class RecipePostRequest extends AbstractRequest
{
    public string $name;
    public array $components = [];

    public function rules(): array
    {
        return [
            new Assert\Collection([
                'id' => new Assert\Optional([
                    new Assert\NotBlank(),
                    new Assert\Uuid(),
                ]),
                'name' => new Assert\Required([
                    new Assert\NotBlank(),
                ]),
                'components' => new Assert\Required([
                    new Assert\NotBlank(),
                    new Assert\Count(min: 1),
                    new Assert\All(
                        new Assert\Collection([
                            'name' => new Assert\Required([
                                new Assert\NotBlank(),
                            ]),
                            'quantity' => new Assert\Required([
                                new Assert\NotBlank(),
                                new Assert\Type(type: 'numeric'),
                                new Assert\Positive(),
                            ]),
                            'unit' => new Assert\Required([
                                new Assert\NotBlank(),
                                new Assert\Choice(Unit::values()),
                            ]),
                        ]),
                    ),
                ]),
            ]),
        ];
    }
}
