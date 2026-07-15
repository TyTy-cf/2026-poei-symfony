<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route("/user/{id}", name : "app_user_show")]
public function showUser(int $id, UserRepository $userRepository){
$user = $userRepository->find($id);
if($user === null){
    return $this->redirectToRoute("/");
}
return $this->render("user/index.html.twig", [
    "user" => $user
        ]);
    }

}
