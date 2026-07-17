<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
  #[Route('/', name: 'app_home_redirect')]
  public function homeRedirect(Request $request): Response
  {
    return $this->redirectToRoute('app_home', [
      '_locale' => $request->getDefaultLocale(),
    ]);
  }


  #[Route('/{_locale}/', name: 'app_home')]
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

    $mostPlayedCategories = $categoryRepository->mostPlayedCategories(5);

    // dd($mostPlayedCategories);
    return $this->render('front/home/index.html.twig', [
      'controller_name' => 'HomeController',
      'latestReleasedGames' => $latestReleasedGames,
      'latestReviews' => $latestReviews,
      'bestRatedGames' => $bestRatedGames,
      'categories' => $categories,
      'mostPlayedGames' => $mostPlayedGames,
      'mostPlayedCategories' => $mostPlayedCategories,
    ]);
  }
}
