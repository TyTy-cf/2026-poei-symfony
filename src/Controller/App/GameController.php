<?php

namespace App\Controller\App;

use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewType;
use App\Repository\GameRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class GameController extends AbstractController
{
    #[Route('/{_locale}/game/search', name: 'app_game_search')]
    public function search(
        GameRepository $gameRepository,
        Request        $request,
    ): Response
    {
        $search = $request->request->get('search');
        $games = $gameRepository->findBySearch($search);

        return $this->render('front/game/search.html.twig', [
            'games' => $games,
            'search' => $search,
        ]);
    }

    #[Route('/{_locale}/game/{slug}', name: 'app_game_show')]
    public function show(
        GameRepository         $gameRepository,
        EntityManagerInterface $em,
        TranslatorInterface    $translator,
        Request                $request,
        string                 $slug
    ): Response
    {
        $game = $gameRepository->findOneFullBy($slug);

        if ($game === null) {
            $this->addFlash('danger', $translator->trans('game.not_found', [], 'alert'));
            return $this->redirectToRoute('app_home');
        }

        $form = null;
        /** @var User $user */
        $user = $this->getUser();

        if ($user !== null) {
            $review = new Review();
            $form = $this->createForm(ReviewType::class, $review);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $review->setGame($game);
                $review->setUser($user);
                $review->setCreatedAt(new DateTimeImmutable());

                try {
                    $em->persist($review);
                    $em->flush();
                    $this->addFlash('success', $translator->trans('review.edit.success', [], 'alert'));
                } catch (\Exception $e) {
                    $this->addFlash('danger', $translator->trans('review.edit.danger', [], 'alert'));
                }

                return $this->redirectToRoute('app_game_show', [
                    'slug' => $game->getSlug()
                ]);
            }
        }

        $similarGames = $gameRepository->findBySimilarCategory($game, 3);

        return $this->render('front/game/show.html.twig', [
            'game' => $game,
            'form' => $form,
            'similarGames' => $similarGames,
        ]);
    }

}
