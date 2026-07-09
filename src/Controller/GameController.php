<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/game/{id}', name: 'app_game')]
    public function index(int $id, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->find($id);
        $gamesPublisher = null;
        if ($game->getPublisher())
        {
            $gamesPublisher = $gameRepository->findBy(['publisher' => $game->getPublisher()->getName()], [], 9);
        }

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController',
            'game' => $game,
            'gamesPublisher' => $gamesPublisher,
        ]);
    }
}
