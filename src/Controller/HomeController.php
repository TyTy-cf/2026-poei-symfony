<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(GameRepository $gameRepository, ReviewRepository $reviewRepository, CategoryRepository $categoryRepository): Response
    {
        $gamesNewest = $gameRepository->findBy([], ['publishedAt' => 'DESC'], 9);
        $gamesOldest = $gameRepository->findBy([], ['publishedAt' => 'ASC'], 9);
        $gamesDesc = $gameRepository->findBy([], ['name' => 'DESC'], 9);
        $reviewsNewest = $reviewRepository->findBy([], ['createdAt' => 'DESC'], 9);
        $categories = $categoryRepository->findBy([], ['name' => 'ASC'], 9);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'gamesNewest' => $gamesNewest,
            'gamesOldest' => $gamesOldest,
            'gamesDesc' => $gamesDesc,
            'reviewsNewest' => $reviewsNewest,
            'categories' => $categories,
        ]);
    }
}
