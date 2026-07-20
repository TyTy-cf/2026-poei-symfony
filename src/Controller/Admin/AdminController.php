<?php

namespace App\Controller\Admin;

use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserOwnGameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{

    #[Route('/{_locale}/admin', name: 'admin_dashboard')]
    public function index(UserOwnGameRepository $userOwnGameRepository, ReviewRepository $reviewRepository, GameRepository $gameRepository): Response
    {
        $lastSells = $userOwnGameRepository->findLastSells(8);
        $latestReview = $reviewRepository->findLatestReview(8);
        $latestGames = $gameRepository->findLatestGames(8);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            "last_sells" => $lastSells,
            "latest_review" => $latestReview,
            "latest_games" => $latestGames
        ]);
    }
}
