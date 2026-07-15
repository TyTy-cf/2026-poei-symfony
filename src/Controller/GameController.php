<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class GameController extends AbstractController
{
    #[Route('/{_locale}/game/{slug}', name: 'app_game_show')]
    public function show(
        GameRepository      $gameRepository,
        TranslatorInterface $translator,
        string              $slug
    ): Response
    {
        $game = $gameRepository->findOneBy(['slug' => $slug]);

        if ($game === null) {
            // add a flashgBag message to session, for next page :
            // ['danger'] => ['Message 1', 'Message 2']
            $this->addFlash('danger', 'Game not found');
            return $this->redirectToRoute('app_home');
        }

        $similarGames = $gameRepository->findBySimilarCategory($game, 3);

        return $this->render('front/game/show.html.twig', [
            'game' => $game,
            'similarGames' => $similarGames,
        ]);
    }
}
