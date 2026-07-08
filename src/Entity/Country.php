<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 5)]
  private ?string $code = null;

  #[ORM\Column(length: 255)]
  private ?string $name = null;

  #[ORM\Column(length: 255)]
  private ?string $nationality = null;

  #[ORM\Column(length: 255, unique: true)]
  private ?string $slug = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $urlFlag = null;

  /**
   * @var Collection<int, Game>
   */
  #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'countries')]
  private Collection $games;


  public function __construct()
  {
      $this->games = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getCode(): ?string
  {
    return $this->code;
  }

  public function setCode(string $code): static
  {
    $this->code = $code;

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

  public function getNationality(): ?string
  {
    return $this->nationality;
  }

  public function setNationality(string $nationality): static
  {
    $this->nationality = $nationality;

    return $this;
  }

  public function getSlug(): ?string
  {
    return $this->slug;
  }

  public function setSlug(string $slug): static
  {
    $this->slug = $slug;

    return $this;
  }

  public function getUrlFlag(): ?string
  {
    return $this->urlFlag;
  }

  public function setUrlFlag(?string $urlFlag): static
  {
    $this->urlFlag = $urlFlag;

    return $this;
  }

  /**
   * @return Collection<int, Game>
   */
  public function getGames(): Collection
  {
      return $this->games;
  }

  public function addGame(Game $game): static
  {
      if (!$this->games->contains($game)) {
          $this->games->add($game);
          $game->addCountry($this);
      }

      return $this;
  }

  public function removeGame(Game $game): static
  {
      if ($this->games->removeElement($game)) {
          $game->removeCountry($this);
      }

      return $this;
  }
}
