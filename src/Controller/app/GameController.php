<?php

namespace App\Controller\app;

use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/game/{slug}/{page}', name: 'app_game')]
    public function index(string $slug, GameRepository $gameRepository, ReviewRepository $reviewRepository, int $page = 1): Response
    {
        $game = $gameRepository->findOneBy(['slug' => $slug]);
        $gamesPublisher = null;
        $reviewsNewest = $reviewRepository->findFullBy(['r.createdAt' => 'DESC'], 10, $page);
        $gamesSimilar = $gameRepository->findByCategories($game->getCategories());

        if ($game->getPublisher())
        {
            $gamesPublisher = $gameRepository->findBy(['publisher' => $game->getPublisher()], [], 9);
        }

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'game' => $game,
            'gamesPublisher' => $gamesPublisher,
            'reviewsNewest' => $reviewsNewest,
            'averageScore' => $reviewRepository->getAverageScore($game),
            'gamesSimilar' => $gamesSimilar,
            'currentPage' => $page,
        ]);
    }
}
