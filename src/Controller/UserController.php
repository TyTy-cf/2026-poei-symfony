<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
  #[Route('/user/{name}', name: 'app_user_show')]
  public function show(UserRepository $userRepository, string $name): Response
  {
    $user = $userRepository->findUser(['name' => $name]);

    if (!$user) {
      return $this->redirectToRoute('app_home');
    }


    // dd($user);

    return $this->render('front/user/show.html.twig', [
      'controller_name' => 'UserController',
      'user' => $user,
    ]);
  }
}
