<?php

namespace App\Export\Products\Infrastructure\Symfony;

use App\Cookery\ProductCategories\Domain\ProductCategoryRepository;
use App\Cookery\Products\Domain\ProductRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'export:products:all',
    description: 'Exports all products',
)]
class ExportProductsCommand extends Command
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductCategoryRepository $categoryRepository,
        private SerializerInterface $serializer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output = new SymfonyStyle($input, $output);

        $categories = $this->categoryRepository->all();
        $serializedCategories = $this->serializer->serialize($categories->toArray(), 'json');

        file_put_contents('assets/product-exports/all.json', $serializedCategories);

        $output->success('Products exported successfully');

        return Command::SUCCESS;
    }
}
