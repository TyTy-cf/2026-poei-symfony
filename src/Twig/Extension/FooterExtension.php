<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\FooterRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FooterExtension extends AbstractExtension
{
  public function getFilters(): array
  {
    return [
      new TwigFilter('mostPlayedCategories', [FooterRuntime::class, 'mostPlayedCategories']),
    ];
  }

  public function getFunctions(): array
  {
    return [
      new TwigFunction('mostPlayedCategories', [FooterRuntime::class, 'mostPlayedCategories']),
    ];
  }
}
