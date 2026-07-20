<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{

  #[Route('/{_locale}/admin/', name: 'admin_dashboard')]
  public function index(GameRepository $gameRepository, ReviewRepository $reviewRepository, UserRepository $userRepository): Response
  {

    $latestReleasedGames = $gameRepository->findLatestReleases(8);
    $LatestGamesSold = $gameRepository->LatestGamesSold(8);
    $LatestReviews = $reviewRepository->findLatestReviews(8);
    $LatestUsers = $userRepository->findLatestUsers(8);

    // dd($LatestUsers);
    return $this->render('admin/home/index.html.twig', [
      'controller_name' => 'HomeController',
      'latestReleasedGames' => $latestReleasedGames,
      'latestGamesSold' => $LatestGamesSold,
      'latestReviews' => $LatestReviews,
      'LatestUsers' => $LatestUsers
    ]);
  }
}
