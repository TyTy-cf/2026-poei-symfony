<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/admin/game/', name: 'admin_game_')]
final class GameController extends AbstractController
{

  #[Route('/', name: 'index')]
  public function index(GameRepository $gameRepository): Response
  {

    $games = $gameRepository->findAllGames();


    return $this->render('admin/game/index.html.twig', [
      'controller_name' => 'HomeController',
      'games' => $games,
    ]);
  }

  #[Route('/edit/{id}', name: 'edit')]
  public function edit(Game $game): Response
  {

    return $this->render('admin/game/edit.html.twig', [
      'controller_name' => 'HomeController',
      'game' => $game,
    ]);
  }

  #[Route('/delete/{id}', name: 'delete')]
  public function delete(Game $game): Response
  {
    die('delete game');

    return $this->render('admin/game/index.html.twig', [
      'controller_name' => 'HomeController',
      'games' => $games,
    ]);
  }

  #[Route('/add/{id}', name: 'add')]
  public function add(Game $game): Response
  {

    return $this->render('admin/game/add.html.twig', [
      'controller_name' => 'HomeController',
      'game' => $game,
    ]);
  }

  #[Route('/{id}', name: 'show')]
  public function show(Game $game): Response
  {

    return $this->render('admin/game/show.html.twig', [
      'controller_name' => 'HomeController',
      'game' => $game,
    ]);
  }



  // #[Route('/show/{id}', name: 'show')]
  // public function show(game $game): Response
  // {

  //   return $this->render('admin/game/show.html.twig', [
  //     'controller_name' => 'HomeController',
  //     'game' => $game,
  //   ]);
  // }




}
