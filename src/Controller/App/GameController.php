<?php

namespace App\Controller\App;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\GameRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


final class GameController extends AbstractController
{
    #[Route('/{_locale}/game/{slug}', name: 'app_game_show')]
    public function show(
        EntityManagerInterface      $em,
        GameRepository      $gameRepository,
        TranslatorInterface $translator,
        string              $slug,
        Request             $request,
    ): Response
    {
        $game = $gameRepository->findOneFullBy($slug);

        if ($game === null) {
            $this->addFlash('danger', $translator->trans('game.not_found', [], 'alert'));
            return $this->redirectToRoute('app_home');
        }

        $loggedUser = $this->getUser();
        $form = null;

        if ($loggedUser !== null) {
            $newReview = new Review();
            $form = $this->createForm(ReviewType::class, $newReview);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $newReview->setCreatedAt(new DateTimeImmutable());
                    $newReview->setUser($loggedUser);
                    $newReview->setGame($game);
                    $em->persist($newReview);
                    $em->flush();
                    return $this->redirectToRoute('app_game_show', ['slug' => $slug]);
                } catch (\Exception $e) {
                    dump($e->getMessage());
                }
            }
        }

        $similarGames = $gameRepository->findBySimilarCategory($game, 3);

        return $this->render('front/game/show.html.twig', [
            'game' => $game,
            'similarGames' => $similarGames,
            'form' => $form,
        ]);
    }


}
