<?php

namespace App\Entity\Example;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TranslatableCountry
{
    private ?int $id = null;

    private ?string $code = null;

    private ?string $urlFlag = null;

    private ?string $keyName = 'entity.country.name';

    private ?string $keyNationality = 'entity.country.nationality';

    private ?string $keySlug = 'entity.country.slug';

    // #[ORM\OneToMany(targetEntity: TranslatableCountry::class, mappedBy: 'translatable')]
    private Collection $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
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

    public function getUrlFlag(): ?string
    {
        return $this->urlFlag;
    }

    public function setUrlFlag(?string $urlFlag): void
    {
        $this->urlFlag = $urlFlag;
    }

    public function getKeyName(): ?string
    {
        return $this->keyName;
    }

    public function setKeyName(?string $keyName): void
    {
        $this->keyName = $keyName;
    }

    public function getKeyNationality(): ?string
    {
        return $this->keyNationality;
    }

    public function setKeyNationality(?string $keyNationality): void
    {
        $this->keyNationality = $keyNationality;
    }

    public function getKeySlug(): ?string
    {
        return $this->keySlug;
    }

    public function setKeySlug(?string $keySlug): void
    {
        $this->keySlug = $keySlug;
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function setTranslations(Collection $translations): void
    {
        $this->translations = $translations;
    }

    public function getTranslation(string $locale): ?TranslationsCountry
    {
        foreach ($this->translations as $translation) {
            if ($translation->getLocale() === $locale) {
                return $translation;
            }
        }
        return null;
    }
}
