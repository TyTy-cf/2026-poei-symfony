<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/game/{slug}', name: 'app_game')]
    public function show(?string $slug, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->find($slug);
        if($game === null){
            return $this->redirectToRoute("app_home");
        }

        return $this->render('front/game/index.html.twig', [
            'game' => $game
        ]);
    }
}
