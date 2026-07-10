<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/game/{slug}', name: 'app_game_show')]
    public function show(?string $slug, GameRepository $gameRepository): Response
    {

        $games = $gameRepository->findOneBy(['slug' => $slug]);
        return $this->render('front/game/show.html.twig', [
            'games' => $games,
        ]);
    }
}
