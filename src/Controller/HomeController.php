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
    public function home(GameRepository $gameRepository, ReviewRepository $reviewRepository, CategoryRepository $categoryRepository): Response
    {
        $games = $gameRepository->findAll();
        $gamesBy = $gameRepository->findBy([], ['publishedAt' => 'DESC'], 9);
        $gamesByPrice = $gameRepository->findBy([], ['price' => 'DESC'], 9);
        $reviews = $reviewRepository->findBy(['rating' => '5'], ['createdAt' => 'DESC'], 5);
        $gamesTop = $gameRepository->findBy([], ['name' => 'ASC'], 6);
        $categories = $categoryRepository->findBy([], ['name' => 'ASC'], 9);


        return $this->render('front/home/index.html.twig', [
            'games' => $games,
            'gamesBy' => $gamesBy,
            'gamesByPrice' => $gamesByPrice,
            'reviews' => $reviews,
            'gamesTop' => $gamesTop,
            'categories' => $categories
        ]);
    }
}
