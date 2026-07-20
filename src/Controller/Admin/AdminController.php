<?php

namespace App\Controller\Admin;

use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserOwnGameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{

    #[Route('/{_locale}/admin', name: 'admin_home')]
    public function index(
        UserOwnGameRepository $userOwnGameRepository,
        ReviewRepository      $reviewRepository,
        GameRepository        $gameRepository,
        UserRepository        $userRepository
    ): Response
    {
        $latestPurchases = $userOwnGameRepository->findLatest(8);
        $latestComments = $reviewRepository->findLatest(8);
        $latestGames = $gameRepository->findBy([], ['publishedAt' => 'DESC'], 8);
        $latestUsers = $userRepository->findLatest(8);

        return $this->render('admin/home/index.html.twig', [
            'latestPurchases' => $latestPurchases,
            'latestComments' => $latestComments,
            'latestGames' => $latestGames,
            'latestUsers' => $latestUsers
        ]);
    }

}
