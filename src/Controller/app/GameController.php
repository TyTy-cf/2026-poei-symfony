<?php

namespace App\Controller\app;

use App\Entity\Review;
use App\Form\CountryType;
use App\Form\ReviewType;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/game/search', name: 'app_game_search')]
    public function search(Request $request, GameRepository $gameRepository): Response
    {
        $searchValue = $request->query->get('search');

        if ($searchValue == '')
        {
            $games = $gameRepository->findAll();
        } else {
            $games = $gameRepository->findBySearch($searchValue);
        }

        return $this->render('front/game/search.html.twig', [
            'games' => $games,
            'searchValue' => $searchValue,
        ]);
    }

    #[Route('/game/{slug}/{page}', name: 'app_game')]
    public function index(string $slug, EntityManagerInterface $entityManager, GameRepository $gameRepository, ReviewRepository $reviewRepository, Request $request, int $page = 1): Response
    {
        $game = $gameRepository->findOneBy(['slug' => $slug]);
        $gamesPublisher = null;
        $reviewsNewest = $reviewRepository->findFullBy(['r.createdAt' => 'DESC'], 10, $page);
        $gamesSimilar = $gameRepository->findByCategories($game->getCategories());

        if ($game->getPublisher())
        {
            $gamesPublisher = $gameRepository->findBy(['publisher' => $game->getPublisher()], [], 9);
        }

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $review->setGame($game)
                ->setUser($this->getUser())
                ->setDownvote(0)
                ->setUpvote(0)
                ->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('app_game', ['slug' => $slug], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/game/index.html.twig', [
            'controller_name' => 'GameController',
            'game' => $game,
            'gamesPublisher' => $gamesPublisher,
            'reviewsNewest' => $reviewsNewest,
            'averageScore' => $reviewRepository->getAverageScore($game),
            'gamesSimilar' => $gamesSimilar,
            'currentPage' => $page,
            'form' => $form,
        ]);
    }
}
