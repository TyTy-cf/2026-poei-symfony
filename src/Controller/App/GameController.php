<?php

namespace App\Controller\App;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class GameController extends AbstractController
{
    #[Route('/{_locale}/game/{slug}', name: 'app_game_show')]
    public function show(
        GameRepository                            $gameRepository,
        TranslatorInterface                       $translator,
        string                                    $slug,
        Request                                   $request,
        EntityManagerInterface                    $entityManager
    ): Response
    {
        $game = $gameRepository->findOneFullBy($slug);

        if ($game === null) {
            $this->addFlash('danger', $translator->trans('game.not_found', [], 'alert'));
            return $this->redirectToRoute('app_home');
        }

        $similarGames = $gameRepository->findBySimilarCategory($game, 3);

        $loggedUser = $this->getUser();
        $form = null;

        if ($loggedUser !== null) {

            $review = new Review();
            $form = $this->createForm(ReviewType::class, $review);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {
                $review->setCreatedAt(new \DateTimeImmutable());
                $review->setUser($loggedUser);
                $review->setGame($game);
                $game->addReview($review);
                $entityManager->persist($review);
                $entityManager->flush();

                $this->addFlash('success', 'Commentaire ajouté !');
                return $this->redirectToRoute('app_game_show', ['slug' => $slug]);
            }

        }


        return $this->render('front/game/show.html.twig', [
            'game' => $game,
            'similarGames' => $similarGames,
            'addReviewForm' => $form,
        ]);
    }

}
