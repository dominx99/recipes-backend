<?php

namespace App\Import\Tag\Infrastructure\Symfony;

use App\Cookery\Tags\Domain\Tag;
use App\Cookery\Tags\Domain\TagCollection;
use App\Cookery\Tags\Domain\TagRepository;
use App\Shared\Domain\ValueObject\Uuid;
use JMS\Serializer\SerializerInterface;

use function Lambdish\Phunctional\map;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'import:tags:all',
    description: 'Import all tags',
)]
class ImportTagsCommand extends Command
{
    public function __construct(
        private TagRepository $tagRepository,
        private SerializerInterface $serializer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->tagRepository->all()->count() > 0) {
            $io = new SymfonyStyle($input, $output);
            $io->error('Tags already imported');

            return Command::FAILURE;
        }

        $output = new SymfonyStyle($input, $output);

        $tagsToImport = json_decode(file_get_contents('assets/tag-exports/all.json'), true);

        foreach ($tagsToImport as $tag) {
            $tag = Tag::new(
                (string) Uuid::random(),
                $tag['name'],
                new TagCollection(map(fn (array $synonym) => Tag::new(
                    (string) Uuid::random(),
                    $synonym['name'],
                ), $tag['synonyms']))
            );

            $this->tagRepository->save($tag);
        }

        $output->success('Tags imported successfully');

        return Command::SUCCESS;
    }
}
