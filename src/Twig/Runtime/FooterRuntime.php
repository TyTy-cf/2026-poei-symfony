<?php

namespace App\Twig\Runtime;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use Twig\Extension\RuntimeExtensionInterface;

readonly class FooterRuntime implements RuntimeExtensionInterface
{

    public function __construct(
        private CategoryRepository $categoryRepository,
        private GameRepository $gameRepository,

    )
    {
    }

    public function bestCategories(): array
    {
        return $this->categoryRepository->findBestCategories(5);
    }

    public function commentedGames(): array
    {
        return $this->gameRepository->findCommentedGames(5);
    }

}
