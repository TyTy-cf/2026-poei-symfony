<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/game/{slug}', name: 'app_game')]
    public function index(string $slug, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->findBy(['slug' => $slug]);
        $gamesPublisher = null;
        if ($game[0]->getPublisher())
        {
            $gamesPublisher = $gameRepository->findBy(['publisher' => $game[0]->getPublisher()->getName()], [], 9);
        }

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'game' => $game[0],
            'gamesPublisher' => $gamesPublisher,
        ]);
    }
}
