<?php

namespace App\Entity\Example;

class TranslationsCountry
{
    private ?int $id = null;

    private ?string $locale = null;

    private ?string $name;

    private ?string $nationality;

    private ?string $slug;

//    #[ORM\ManyToOne(targetEntity: TranslatableCountry::class, inversedBy: 'translations')]
    private TranslatableCountry $translatable;

    public function getId(): ?int
    {
        return $this->id;
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
}
