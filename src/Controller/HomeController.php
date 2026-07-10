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

    //doit être les 9 derniers jeux sortis
    $latestReleasedGames = $gameRepository->findLatestReleases(9);

    // les 6 jeux avec le meilleur rating
    $bestRatedGames = $gameRepository->findByPopularity(6);

    $latestReviews = $reviewRepository->findBy([], ["upvote" => "DESC"], 6);

    // les 9 jeux les plus joués (Query custom !)
    $mostPlayedGames = $gameRepository->mostPlayedGames(9);

    $categories = $categoryRepository->findBy([], ["name" => "ASC"], 9);


    return $this->render('home/index.html.twig', [
      'controller_name' => 'HomeController',
      'latestReleasedGames' => $latestReleasedGames,
      'latestReviews' => $latestReviews,
      'bestRatedGames' => $bestRatedGames,
      'categories' => $categories,
      'mostPlayedGames' => $mostPlayedGames,
    ]);
  }
}
