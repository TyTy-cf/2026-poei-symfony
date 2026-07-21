<?php

namespace App\Controller\app;

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
        $gamesBest = $gameRepository->findBy([], ['publishedAt' => 'DESC'], 9);
        $reviewsNewest = $reviewRepository->findFullByRatingMax(5);
        $categories = $categoryRepository->findBy([], ['name' => 'ASC'], 9);

        $gamesTrending = $gameRepository->findTrending();
        $gamesTop = $gameRepository->findTop();

        return $this->render('front/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'gamesTrending' => $gamesTrending,
            'gamesBest' => $gamesBest,
            'gamesTop' => $gamesTop,
            'reviewsNewest' => $reviewsNewest,
            'categories' => $categories,
        ]);
    }
}
