<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\Service\SlugifyService;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/admin/game', name: 'admin_game_')]
final class GameController extends AbstractController
{
  #[Route(name: 'index', methods: ['GET'])]
  public function index(GameRepository $gameRepository): Response
  {
    return $this->render('admin/game/index.html.twig', [
      'games' => $gameRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager, SlugifyService $slugifyService): Response
  {
    $game = new Game();
    $form = $this->createForm(GameType::class, $game);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $game->setSlug($slugifyService->slugify($game->getName()));
      $entityManager->persist($game);
      $entityManager->flush();

      return $this->redirectToRoute('admin_game_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('admin/game/new.html.twig', [
      'game' => $game,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'show', methods: ['GET'])]
  public function show(Game $game): Response
  {
    return $this->render('admin/game/show.html.twig', [
      'game' => $game,
    ]);
  }

  #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Game $game, EntityManagerInterface $entityManager, SlugifyService $slugifyService): Response
  {
    $form = $this->createForm(GameType::class, $game);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $game->setSlug($slugifyService->slugify($game->getName()));
      $entityManager->flush();

      return $this->redirectToRoute('admin_game_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('admin/game/edit.html.twig', [
      'game' => $game,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'delete', methods: ['POST'])]
  public function delete(Request $request, Game $game, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->getPayload()->getString('_token'))) {
      $entityManager->remove($game);
      $entityManager->flush();
    }

    return $this->redirectToRoute('admin_game_index', [], Response::HTTP_SEE_OTHER);
  }
}
