<?php

namespace App\Twig\Runtime;

use App\Repository\CategoryRepository;
use Twig\Extension\RuntimeExtensionInterface;

class FooterRuntime implements RuntimeExtensionInterface
{
  public function __construct(
    private CategoryRepository $mostPlayedCategoriesService
  ) {}

  public function mostPlayedCategories(int $value): array
  {
    return $this->mostPlayedCategoriesService->mostPlayedCategories($value);
  }
}
