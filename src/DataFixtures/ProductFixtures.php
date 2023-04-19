<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Cookery\ProductCategories\Domain\ProductCategory;
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
        $categories = [
            'Vegetables' => [
                'tomato' => ['tomatoes'],
                'potato' => ['potatoes'],
                'onion' => ['onions'],
                'carrot' => ['carrots'],
                'cucumber' => ['cucumbers'],
                'lettuce' => ['lettuce'],
                'garlic' => ['garlic'],
                'celery' => ['celery'],
                'broccoli' => ['broccoli'],
                'asparagus' => ['asparagus'],
                'zucchini' => ['zucchini'],
                'spinach' => ['spinach'],
                'mushroom' => ['mushrooms'],
                'cabbage' => ['cabbage'],
                'cauliflower' => ['cauliflower'],
                'beetroot' => ['beetroot'],
                'radish' => ['radish'],
            ],
            'Fruits' => [
                'apple' => ['apples'],
                'banana' => ['bananas'],
                'orange' => ['oranges'],
                'grape' => ['grapes'],
                'strawberry' => ['strawberries'],
                'pineapple' => ['pineapples'],
                'watermelon' => ['watermelons'],
                'pear' => ['pears'],
                'kiwi' => ['kiwis'],
                'mango' => ['mangoes'],
                'lemon' => ['lemons'],
                'peach' => ['peaches'],
                'cherry' => ['cherries'],
                'apricot' => ['apricots'],
                'plum' => ['plums'],
                'coconut' => ['coconuts'],
                'pomegranate' => ['pomegranates'],
            ],
            'Meat' => [
                'chicken' => [],
                'beef' => [],
                'pork' => [],
                'lamb' => [],
                'turkey' => [],
                'duck' => [],
                'goose' => [],
            ],
            'Fish' => [
                'salmon' => [],
                'tuna' => [],
                'cod' => [],
                'haddock' => [],
                'hake' => [],
                'mackerel' => [],
                'trout' => [],
                'sardines' => [],
                'anchovies' => [],
                'prawns' => [],
                'shrimp' => [],
                'lobster' => [],
                'crab' => [],
                'oysters' => [],
                'clams' => [],
                'mussels' => [],
            ],
            'Dairy' => [
                'milk' => [],
                'butter' => [],
                'cheese' => [],
                'yogurt' => [],
                'cream' => [],
                'ice cream' => [],
            ],
            'Bakery' => [
                'bread' => [],
                'rolls' => [],
                'bagels' => [],
                'croissants' => [],
                'buns' => [],
                'biscuits' => [],
                'cakes' => [],
                'cookies' => [],
            ],
            'Drinks' => [
                'water' => [],
                'juice' => [],
                'soda' => [],
                'tea' => [],
                'coffee' => [],
                'beer' => [],
                'wine' => [],
                'vodka' => [],
                'whiskey' => [],
            ],
            'Sweets' => [
                'chocolate' => [],
                'candy' => [],
                'lollipop' => [],
                'marshmallow' => [],
                'gummy bear' => [],
                'gum' => [],
                'caramel' => [],
                'ice lolly' => [],
            ],
            'Cereals' => [
                'rice' => [],
                'pasta' => [],
                'noodles' => [],
                'couscous' => [],
                'oats' => [],
                'cornflakes' => [],
                'muesli' => [],
                'granola' => [],
                'bread crumbs' => [],
            ],
            'Spices' => [
                'salt' => [],
                'pepper' => [],
                'cumin' => [],
                'coriander' => [],
                'cinnamon' => [],
                'ginger' => [],
            ],
            'Condiments' => [
                'ketchup' => [],
                'mayonnaise' => [],
                'mustard' => [],
                'vinegar' => [],
                'soy sauce' => [],
                'tabasco' => [],
                'hot sauce' => [],
            ],
            'Other' => [
                'eggs' => [],
                'oil' => [],
                'sugar' => [],
                'flour' => [],
                'baking powder' => [],
                'baking soda' => [],
                'vanilla' => [],
                'cocoa' => [],
                'honey' => [],
                'jam' => [],
                'nutella' => [],
                'marmalade' => [],
                'syrup' => [],
                'sauce' => [],
                'salsa' => [],
                'chutney' => [],
                'pickles' => [],
                'olives' => [],
            ],
        ];

        foreach ($categories as $categoryName => $products) {
            $productEntities = map(function ($synonyms, $name) {
                $product = Product::new(
                    Uuid::random(),
                    $name,
                    new ProductCollection(map(fn (string $synonym) => Product::new(
                        Uuid::random(),
                        $synonym,
                    ),
                        $synonyms,
                    ))
                );

                return $product;
            }, $products);

            $category = ProductCategory::new(
                Uuid::random(),
                $categoryName,
                new ProductCollection($productEntities)
            );

            $manager->persist($category);
        }

        $manager->flush();
    }
}
