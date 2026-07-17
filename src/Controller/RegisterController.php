<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
  #[Route('{_locale}/register', name: 'app_register')]
  public function register(Request $request, EntityManagerInterface $em): Response
  {

    $user = new User();
    $form = $this->createForm(RegisterType::class, $user);
    $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {
      try {
        $user->setCreatedAt(new \DateTimeImmutable());
        $em->persist($user);
        $em->flush();
        $this->addFlash(
          'success',
          'User created successfully.'
        );

        return $this->redirectToRoute('app_home');
      } catch (\Exception $e) {
        $this->addFlash(
          'danger',
          'An error occurred while creating the user.'
        );
      }
    }


    return $this->render('front/user/register.html.twig', [
      'controller_name' => 'RegisterController',
      'form' => $form,
    ]);
  }
}
