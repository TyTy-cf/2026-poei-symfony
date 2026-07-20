<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\FooterRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FooterExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('best_categories', [FooterRuntime::class, 'bestCategories']),
            new TwigFunction('commented_games', [FooterRuntime::class, 'commentedGames']),

        ];
    }

}
