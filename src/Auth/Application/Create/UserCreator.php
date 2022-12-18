<?php

declare(strict_types=1);

namespace App\Auth\Application\Create;

use App\Auth\Domain\User;
use App\Auth\Infrastructure\Persistence\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserCreator
{
    public function __construct(
        private UserRepository $repository,
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    public function __invoke(User $user)
    {
        $user->setPassword($this->hasher->hashPassword($user, $user->getPlainPassword()));
        $user->setRoles(['ROLE_USER']);

        $this->repository->save($user);
    }
}
