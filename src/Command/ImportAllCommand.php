<?php

namespace App\Command;

use App\Import\Application\ImportAllSourcesCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsCommand(
    name: 'import:all',
    description: 'Import all recipe sources',
)]
class ImportAllCommand extends Command
{
    public function __construct(private MessageBusInterface $messageBus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->messageBus->dispatch(new ImportAllSourcesCommand());
        } catch (Throwable $e) {
            $output->writeln((string) $e);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
