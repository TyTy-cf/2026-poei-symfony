<?php

namespace App\Service;

use App\Repository\categoryRepository;

class MostPlayedCategories
{
  public function __construct(
    private CategoryRepository $categoryRepository
  ) {}

  public function mostPlayedCategories(int $value): array
  {
    return $this->categoryRepository->mostPlayedCategories($value);
  }
}
