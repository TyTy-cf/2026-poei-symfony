<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserOwnGameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user/{name}', name: 'app_user_show')]
    public function index(
        UserRepository $userRepository,
        string         $name,
        UserOwnGameRepository $userOwnGameRepository,
    ): Response
    {
        $user = $userRepository->findOneBy(['name' => $name]);

        return $this->render('front/user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
