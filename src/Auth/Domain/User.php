<?php

namespace App\Auth\Domain;

use App\Auth\Infrastructure\Persistence\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\Exclude;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'email_uidx', columns: ['email'])]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid_string', unique: true)]
    #[JMS\Type(name: 'string')]
    private ?UuidInterface $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Email(groups: ['POST'])]
    #[Assert\NotBlank(groups: ['POST'])]
    private ?string $email = null;

    #[Assert\NotBlank(groups: ['POST'])]
    #[Assert\Length(min: 8, max: 60, groups: ['POST'])]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255)]
    #[Exclude()]
    private ?string $password = null;

    #[ORM\Column]
    #[Exclude()]
    private array $roles = [];

    /**
     * @param array<int,string> $roles
     */
    public function __construct(
        UuidInterface $id,
        string $email,
        array $roles = [],
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->roles = $roles;
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @param array<int,string> $roles
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;

        return $this;
    }
}
