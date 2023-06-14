<?php

declare(strict_types=1);

namespace App\Auth\Application\Create;

use App\Auth\Domain\User;
use App\Auth\Infrastructure\Persistence\UserRepository;
use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserCreator
{
    public function __construct(
        private UserRepository $repository,
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    public function __invoke(User $userForm): void
    {
        $user = new User(
            Uuid::random(),
            $userForm->getEmail(),
            ['ROLE_USER']
        );

        $user->setPassword($this->hasher->hashPassword($userForm, $userForm->getPlainPassword()));

        $this->repository->save($user);
    }
}
