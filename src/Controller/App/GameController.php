<?php

namespace App\Controller\App;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

final class GameController extends AbstractController
{
    #[Route('/{_locale}/game/{slug}', name: 'app_game_show')]
    public function show(
        EntityManagerInterface      $em,
        GameRepository      $gameRepository,
        TranslatorInterface $translator,
        string              $slug,
        Request $request,
    ): Response
    {
        $formReview = null;
        $loggedUser = $this->getUser();
        $game = $gameRepository->findOneFullBy($slug);
        if ($loggedUser !== null){

            $newReview = new Review();
            $formReview = $this->createForm(ReviewType::class, $newReview);

            $formReview->handleRequest($request);

            if ($formReview->isSubmitted() && $formReview->isValid()) {
                try {
                    $newReview->setCreatedAt(new \DateTimeImmutable());
                    $newReview->setGame($game);
                    $newReview->setUser($loggedUser);
                    $em->persist($newReview);
                    $em->flush();
                    $this->addFlash('success', $translator->trans('register.success', [], 'alert'));
                } catch (\Exception $e) {
                    $this->addFlash('danger', $translator->trans('register.danger', [], 'alert'));

                }
            }
        }

        if ($game === null) {
            $this->addFlash('danger', $translator->trans('game.not_found', [], 'alert'));
            return $this->redirectToRoute('app_home');
        }

        $similarGames = $gameRepository->findBySimilarCategory($game, 3);

        return $this->render('front/game/show.html.twig', [
            'game' => $game,
            'similarGames' => $similarGames,
            "formReview" => $formReview,
        ]);
    }

}
