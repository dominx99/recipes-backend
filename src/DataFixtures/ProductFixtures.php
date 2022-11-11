<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Cookery\Products\Domain\Product;
use App\Cookery\Products\Domain\ProductCollection;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use function Lambdish\Phunctional\map;

final class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $products = [
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

        foreach ($products as $name => $synonyms) {
            $product = Product::new(
                (string) Uuid::random(),
                $name,
                new ProductCollection(map(fn (string $synonym) => Product::new(
                    (string) Uuid::random(),
                    $synonym,
                ),
                    $synonyms
                ))
            );

            $manager->persist($product);
        }

        $manager->flush();
    }
}
