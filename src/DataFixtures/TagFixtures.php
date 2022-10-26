<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Cookery\Tags\Domain\Tag;
use App\Cookery\Tags\Domain\TagCollection;
use App\Cookery\Tags\Domain\TagName;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use function Lambdish\Phunctional\map;

final class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tags = [
            'tomato' => ['tomatoes'],
            'milk' => [],
            'bread' => [],
            'butter' => [],
            'cheese' => [],
            'chicken' => [],
            'beef' => [],
            'pork' => [],
            'fish' => [],
            'egg' => ['eggs'],
            'potato' => ['potatoes'],
            'onion' => [],
            'garlic' => [],
            'carrot' => [],
            'cucumber' => [],
            'lettuce' => [],
            'apple' => [],
            'orange' => [],
            'banana' => [],
            'strawberry' => [],
            'raspberry' => [],
            'blueberry' => [],
            'chocolate' => [],
            'sugar' => [],
            'salt' => [],
            'pepper' => [],
            'flour' => [],
            'rice' => [],
            'pasta' => [],
            'oil' => [],
            'vinegar' => [],
            'water' => [],
            'beer' => [],
            'wine' => [],
            'whiskey' => [],
            'coffee' => [],
            'tea' => [],
            'soda' => [],
            'juice' => [],
            'milkshake' => [],
            'yogurt' => [],
            'ice cream' => [],
            'chips' => [],
        ];

        foreach ($tags as $name => $synonyms) {
            $tag = Tag::new(
                (string) Uuid::random(),
                new TagName($name),
                new TagCollection(map(fn (string $synonym) => Tag::new(
                    (string) Uuid::random(),
                    new TagName($synonym),
                ),
                    $synonyms
                ))
            );

            $manager->persist($tag);
        }

        $manager->flush();
    }
}
