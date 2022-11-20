<?php

namespace App\Import\Products\Infrastructure\Symfony;

use App\Cookery\ProductCategories\Domain\ProductCategory;
use App\Cookery\ProductCategories\Domain\ProductCategoryRepository;
use App\Cookery\Products\Domain\Product;
use App\Cookery\Products\Domain\ProductCollection;
use App\Cookery\Products\Domain\ProductRepository;
use App\Shared\Domain\ValueObject\Uuid;

use function Lambdish\Phunctional\map;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[AsCommand(
    name: 'import:products:all',
    description: 'Import all products',
)]
class ImportProductsCommand extends Command
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductCategoryRepository $categoryRepository,
        private DenormalizerInterface $denormalizer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->productRepository->all()->count() > 0) {
            $io = new SymfonyStyle($input, $output);
            $io->error('Products already imported');

            return Command::FAILURE;
        }

        $output = new SymfonyStyle($input, $output);

        $categoriesToAdd = json_decode(file_get_contents('assets/product-exports/all.json'), true);

        foreach ($categoriesToAdd as $categoryToAdd) {
            $products = new ProductCollection(array_map(fn (array $productToAdd) =>
                Product::new(
                    (string) Uuid::random(),
                    $productToAdd['name'],
                    new ProductCollection(map(fn (array $synonym) => Product::new(
                        (string) Uuid::random(),
                        $synonym['name'],
                    ), $productToAdd['synonyms']))
                )
            , $categoryToAdd['products']));

            $category = new ProductCategory(
                Uuid::random()->value(),
                $categoryToAdd['name'],
                $products
            );

            $this->categoryRepository->save($category, true);
        }

        $output->success('Products imported successfully');

        return Command::SUCCESS;
    }
}
