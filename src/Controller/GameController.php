<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {
        return $this->render('front/game_show/index.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }



    #[Route('/game/{slug}', name: 'app_game_show')]
    public function show(int $slug, GameRepository $gameRepository)
    {
        $game =$gameRepository->find($slug);

        if($game === null){
            return $this->redirectToRoute("app_home");
        }
        return $this->render('front/game_show/index.html.twig', [
            'game' => $game
        ]);
    }


}
