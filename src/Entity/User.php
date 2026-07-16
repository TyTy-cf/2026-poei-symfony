<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Country $country = null;

    #[ORM\Column(length: 255, unique: true)]
    #[NotBlank(message: 'user.email.not_blank')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    #[Regex(
        pattern: '/^(?=.*[0-9])(?=.*[A-Z])(?=.*[@!]).{9,}$/',
        message: 'user.password.regex'
    )]
    #[Length(min: 9, minMessage: 'user.password.min')]
    #[NotBlank(message: 'user.password.not_blank')]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Length(min: 4, minMessage: 'user.name.min')]
    #[NotBlank(message: 'user.name.not_blank')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Length(min: 1, minMessage: 'user.nickname.min')]
    #[NotBlank(message: 'user.nickname.not_blank')]
    private ?string $nickname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profileImage = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $wallet = 0;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'user')]
    private Collection $reviews;

    /**
     * @var Collection<int, UserOwnGame>
     */
    #[ORM\OneToMany(targetEntity: UserOwnGame::class, mappedBy: 'user')]
    private Collection $userOwnGames;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->userOwnGames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
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

    public function getWallet(): int
    {
        return $this->wallet;
    }

    public function setWallet(int $wallet): static
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserOwnGame>
     */
    public function getUserOwnGames(): Collection
    {
        return $this->userOwnGames;
    }

    public function addUserOwnGame(UserOwnGame $userOwnGame): static
    {
        if (!$this->userOwnGames->contains($userOwnGame)) {
            $this->userOwnGames->add($userOwnGame);
            $userOwnGame->setUser($this);
        }

        return $this;
    }

    public function removeUserOwnGame(UserOwnGame $userOwnGame): static
    {
        if ($this->userOwnGames->removeElement($userOwnGame)) {
            // set the owning side to null (unless already changed)
            if ($userOwnGame->getUser() === $this) {
                $userOwnGame->setUser(null);
            }
        }

        return $this;
    }

    public function getTotalGameTime(): int
    {
        $total = 0;

        foreach ($this->getUserOwnGames() as $userOwnGame) {
            $total += $userOwnGame->getGameTime();
        }

        return $total;
    }
}
