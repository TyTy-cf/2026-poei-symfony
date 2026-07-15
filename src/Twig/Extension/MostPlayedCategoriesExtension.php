<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\MostPlayedCategoriesRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MostPlayedCategoriesExtension extends AbstractExtension
{
  public function getFilters(): array
  {
    return [
      new TwigFilter('mostPlayedCategories', [MostPlayedCategoriesRuntime::class, 'mostPlayedCategories']),
    ];
  }

  public function getFunctions(): array
  {
    return [
      new TwigFunction('mostPlayedCategories', [MostPlayedCategoriesRuntime::class, 'mostPlayedCategories']),
    ];
  }
}
