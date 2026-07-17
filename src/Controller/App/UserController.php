<?php

namespace App\Controller\App;

use App\Form\RegisterType;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
  #[Route('/{_locale}/user/{name}', name: 'app_user_show')]
  public function index(
    UserRepository $userRepository,
    Request        $request,
    string         $name,
  ): Response {

    $user = $userRepository->findUser(['name' => $name]);
    $isMe = false;
    $loggedUser = $this->getUser();

    if ($loggedUser !== null && $user->getEmail() === $loggedUser->getUserIdentifier()) {
      $isMe = true;
    }

    $form = null;
    if ($isMe) {
      // Ajouter un form d'édition de profil
      $form = $this->createForm(RegisterType::class, $user);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        // Modif user en DB
      }
    }

    return $this->render('front/user/show.html.twig', [
      'user' => $user,
      'isMe' => $isMe,
      'form' => $form,
    ]);
  }
}
