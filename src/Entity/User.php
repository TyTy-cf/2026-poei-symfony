<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255, unique: true)]
  private ?string $email = null;

  #[ORM\Column]
  private array $roles = [];

  #[ORM\Column(length: 255)]
  private ?string $password = null;

  #[ORM\Column]
  private ?\DateTime $createdAt = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\Column(length: 255)]
  private ?string $nickname = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $profileImage = null;

  #[ORM\Column]
  private ?int $wallet = 0;

  #[ORM\ManyToOne]
  private ?Country $country = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): static
  {
    $this->email = $email;

    return $this;
  }

  public function getRoles(): array
  {
    return $this->roles;
  }

  public function setRoles(array $roles): static
  {
    $this->roles = $roles;

    return $this;
  }

  public function getPassword(): ?string
  {
    return $this->password;
  }

  public function setPassword(string $password): static
  {
    $this->password = $password;

    return $this;
  }

  public function getCreatedAt(): ?\DateTime
  {
    return $this->createdAt;
  }

  public function setCreatedAt(\DateTime $createdAt): static
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function getNickname(): ?string
  {
    return $this->nickname;
  }

  public function setNickname(string $nickname): static
  {
    $this->nickname = $nickname;

    return $this;
  }

  public function getProfileImage(): ?string
  {
    return $this->profileImage;
  }

  public function setProfileImage(?string $profileImage): static
  {
    $this->profileImage = $profileImage;

    return $this;
  }

  public function getWallet(): ?int
  {
    return $this->wallet;
  }

  public function setWallet(int $wallet): static
  {
    $this->wallet = $wallet;

    return $this;
  }

  public function getCountry(): ?Country
  {
    return $this->country;
  }

  public function setCountry(?Country $country): static
  {
    $this->country = $country;

    return $this;
  }
}
