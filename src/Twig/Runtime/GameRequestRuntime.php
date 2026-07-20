<?php

namespace App\Twig\Runtime;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use Twig\Extension\RuntimeExtensionInterface;

class GameRequestRuntime implements RuntimeExtensionInterface
{
    private GameRepository $gameRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(GameRepository $gameRepository, CategoryRepository $categoryRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getPopularGames()
    {
        return $this->gameRepository->findByCommentAmount();
    }

    public function getPopularCategories()
    {
        return $this->categoryRepository->findByMostPlayed();
    }

    public function getCategories()
    {
        return $this->categoryRepository->findAll();
    }
}
