<?php

namespace App\Controller;


use App\Repository\GameRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
  #[Route('/game/{slug}', name: 'app_game_show')]
  public function index(Request $request, GameRepository $gameRepository, string $slug): Response
  {
    $game = $gameRepository->findOneGameAndDetails(['slug' => $slug]);

    if (!$game) {
      $this->addFlash(
        'danger',
        'Game not found.'
      );
      return $this->redirectToRoute('app_home', [
        '_locale' => $request->getDefaultLocale(),
      ]);
    }


    return $this->render('game/show.html.twig', [
      'controller_name' => 'GameController',
      'game' => $game,
    ]);
  }
}
