<?php

namespace App\Controller\app;

use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user/{name}', name: 'app_user')]
    public function index(string $name, UserRepository $userRepository, GameRepository $gameRepository, ReviewRepository $reviewRepository): Response
    {
        $user = $userRepository->findOneBy(['name' => $name]);
        $games = $gameRepository->findByUser($user);
        $reviews = $reviewRepository->findByUser($user);
        $totalGameTime = 0;
        $isMe = false;
        $loggedUser = $this->getUser();

        foreach ($games as $game) {
            $totalGameTime += $game->getUserOwnGames()[0]->getGameTime();
        }

        if ($loggedUser !== null && $user->getEmail() === $this->getUser()->getUserIdentifier()) {
            $isMe = true;
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'games' => $games,
            'totalGameTime' => $totalGameTime,
            'reviews' => $reviews,
            'isMe' => $isMe
        ]);
    }
}
