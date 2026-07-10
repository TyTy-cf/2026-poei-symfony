<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user/{name}', name: 'app_user_show')]
    public function show(?string $name, UserRepository $userRepository, GameRepository $gameRepository): Response
    {

        $users = $userRepository->findOneBy(['name' => $name]);
        return $this->render('front/user/show.html.twig', [
            'users' => $users,
        ]);
    }
}
