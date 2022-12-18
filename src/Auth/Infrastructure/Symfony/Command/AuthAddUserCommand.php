<?php

namespace App\Auth\Infrastructure\Symfony\Command;

use App\Auth\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Throwable;

#[AsCommand(
    name: 'auth:add-user',
    description: 'Adds user',
)]
class AuthAddUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        ?string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addOption('email', 'u', InputOption::VALUE_REQUIRED)
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'Plain Password (not hashed one)')
            ->addOption('roles', 'r', InputOption::VALUE_OPTIONAL, 'Roles that user has to belong. Example "ROLE_USER, ROLE_ADMIN"', [
                'ROLE_USER',
            ])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $user = (new User())
                ->setEmail($input->getOption('email'))
                ->setRoles($input->getOption('roles'))
            ;

            $user->setPassword($this->passwordHasher->hashPassword($user, $input->getOption('password')));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            $io->error(['Failed to add user', (string) $e]);

            return Command::FAILURE;
        }

        $io->success('Successfully created user');

        return Command::SUCCESS;
    }
}
