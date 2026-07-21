<?php

namespace App\Controller\App;

use App\Entity\Review;
use App\Form\ReviewType;
use DateTimeImmutable;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{

  #[Route('/{_locale}/game/search', name: 'app_game_search')]
  public function search(
    Request $request,
    GameRepository $gameRepository,
    PaginatorInterface $paginator
  ): Response {

    $query = $request->query->get('query', '');
    $games = $gameRepository->findBySlug($query);
    $form = null;

    if (!$games) {
      $this->addFlash(
        'danger',
        'Game was not found.'
      );
      return $this->redirectToRoute('app_home', [
        '_locale' => $request->getDefaultLocale(),
      ]);
    }



    if ($games && count($games) > 1) {
      return $this->render('front/game/index.html.twig', [
        'controller_name' => 'GameController',
        'searchGames' => $games,
        'query' => $query,
      ]);
    } else {
      $paginatedReviews = $paginator->paginate(
        $games[0]->getReviews(),
        $request->query->getInt('page', 1),
        12
      );

      return $this->render('front/game/show.html.twig', [
        'controller_name' => 'GameController',
        'game' => $games[0],
        'query' => $query,
        'form' => $form,
        'paginatedReviews' => $paginatedReviews
      ]);
    }
  }


  #[Route('/{_locale}/game/{slug}', name: 'app_game_show')]
  public function show(
    Request $request,
    GameRepository $gameRepository,
    PaginatorInterface $paginator,
    EntityManagerInterface      $em,
    string $slug
  ): Response {
    $game = $gameRepository->findOneFullBy($slug);
    $form = null;

    if (!$game) {
      $this->addFlash(
        'danger',
        'Game not found.'
      );
      return $this->redirectToRoute('app_home', [
        '_locale' => $request->getDefaultLocale(),
      ]);
    }

    $paginatedReviews = $paginator->paginate(
      $game->getReviews(),
      $request->query->getInt('page', 1),
      12
    );

    if ($this->getUser() !== null) {
      $review = new Review();
      $form = $this->createForm(ReviewType::class, $review);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $review->setCreatedAt(new DateTimeImmutable());
        $review->setGame($game);
        $review->setUser($this->getUser());

        try {
          $em->persist($review);
          $em->flush();
          $this->addFlash('success', 'your review has been added successfully.');
          return $this->redirectToRoute('app_home');
        } catch (\Exception $e) {
          $this->addFlash('danger', 'An error occurred while adding your review.');
          dump($e->getMessage());
        }
      }
    }

    return $this->render('front/game/show.html.twig', [
      'controller_name' => 'GameController',
      'game' => $game,
      'paginatedReviews' => $paginatedReviews,
      'form' => $form,
    ]);
  }
}
