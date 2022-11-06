<?php

namespace App\Export\Tag\Infrastructure\Symfony;

use App\Cookery\Tags\Domain\TagRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'export:tags:all',
    description: 'Exports all tags',
)]
class ExportTagsCommand extends Command
{
    public function __construct(
        private TagRepository $tagRepository,
        private SerializerInterface $serializer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output = new SymfonyStyle($input, $output);

        $tags = $this->tagRepository->all();

        $serializedTags = $this->serializer->serialize($tags->toArray(), 'json');

        file_put_contents('assets/tag-exports/all.json', $serializedTags);

        $output->success('Tags exported successfully');

        return Command::SUCCESS;
    }
}
