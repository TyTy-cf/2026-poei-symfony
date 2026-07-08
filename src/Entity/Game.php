<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?Publisher $publisher = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnailCover = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnailCoverLink = null;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'game')]
    private Collection $reviews;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'games')]
    private Collection $category;

    /**
     * @var Collection<int, Country>
     */
    #[ORM\ManyToMany(targetEntity: Country::class, inversedBy: 'games')]
    private Collection $country;



    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->country = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function setPublisher(?Publisher $publisher): static
    {
        $this->publisher = $publisher;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getThumbnailCover(): ?string
    {
        return $this->thumbnailCover;
    }

    public function setThumbnailCover(?string $thumbnailCover): static
    {
        $this->thumbnailCover = $thumbnailCover;

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

    public function getThumbnailCoverLink(): ?string
    {
        return $this->thumbnailCoverLink;
    }

    public function setThumbnailCoverLink(?string $thumbnailCoverLink): static
    {
        $this->thumbnailCoverLink = $thumbnailCoverLink;

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
            $review->setGame($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getGame() === $this) {
                $review->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $catEgory): static
    {
        if (!$this->category->contains($catEgory)) {
            $this->category->add($catEgory);
        }

        return $this;
    }

    public function removeCategory(Category $catEgory): static
    {
        $this->category->removeElement($catEgory);

        return $this;
    }

    /**
     * @return Collection<int, Country>
     */
    public function getCountry(): Collection
    {
        return $this->country;
    }

    public function addCountry(Country $country): static
    {
        if (!$this->country->contains($country)) {
            $this->country->add($country);
        }

        return $this;
    }

    public function removeCountry(Country $country): static
    {
        $this->country->removeElement($country);

        return $this;
    }
}
