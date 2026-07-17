<?php

namespace App\Controller\Admin;

use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    #[Route(path: '/{_locale}/admin', name: 'app_admin')]
    public function admin(GameRepository $gameRepository, UserRepository $userRepository): Response
    {

        $lastGame = $gameRepository->latestGames(5);
        dump($lastGame);


        return $this->render('admin/dashboard.html.twig', [
            'last_game' => $lastGame,
        ]);

    }



//    #[Route(path: '/logout', name: 'app_logout')]
//    public function logout(): void
//    {
//        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
//    }
}
