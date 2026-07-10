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

    $latestGames = $gameRepository->findBy([], ['publishedAt' => 'DESC'], 9);
    $highestPricedGames = $gameRepository->findBy([], ["price" => "DESC"], 9);
    $latestReviews = $reviewRepository->findBy([], ["upvote" => "DESC"], 6);

    $topGames = $gameRepository->findBy([], ["name" => "DESC"], 6);

    $categories = $categoryRepository->findBy([], ["name" => "ASC"], 9);


    return $this->render('home/index.html.twig', [
      'controller_name' => 'HomeController',
      'latestGames' => $latestGames,
      'latestReviews' => $latestReviews,
      'highestPricedGames' => $highestPricedGames,
      'categories' => $categories,
      'topGames' => $topGames,
    ]);
  }
}
