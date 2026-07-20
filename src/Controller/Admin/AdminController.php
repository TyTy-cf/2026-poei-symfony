<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{

  #[Route('/{_locale}/admin/', name: 'admin_dashboard')]
  public function index(GameRepository $gameRepository, ReviewRepository $reviewRepository, CategoryRepository $categoryRepository): Response
  {

    //doit être les 9 derniers jeux sortis
    $latestReleasedGames = $gameRepository->findLatestReleases(8);
    $LatestGamesSold = $gameRepository->LatestGamesSold(8);
    // $LatestReviews = $reviewRepository->findLatestReviews(8);

    dd($LatestGamesSold);
    return $this->render('admin/home/index.html.twig', [
      'controller_name' => 'HomeController',
      'latestReleasedGames' => $latestReleasedGames,
      'latestGamesSold' => $LatestGamesSold
      // 'latestReviews' => $LatestReviews,
    ]);
  }
}
