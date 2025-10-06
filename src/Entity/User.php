<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 64)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 32)]
    private ?string $city = null;

    /**
     * Get the ID of the user.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the email for the user.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the email for the user.
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * Set the username for the user.
     *
     * This method is provided for compatibility with UserInterface, but
     * it is recommended to use getUserIdentifier() instead.
     *
     * @see UserInterface
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     *
     * return $this
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the city for the user.
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Set the city for the user.
     *
     * @param string $city
     *
     * @return $this
     */
    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the hashed password for the user.
     *
     * @see PasswordAuthenticatedUserInterface
     *
     * @return string|null The hashed password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the hashed password for the user.
     *
     * @param string $password The hashed password
     *
     * @return $this
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     *
     * @return array<string, mixed>
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    /**
     * This method is deprecated since Symfony 5.3 and will be removed in Symfony 8.0.
     *
     * @see UserInterface
     */
    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }
}
