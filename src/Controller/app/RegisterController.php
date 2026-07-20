<?php

namespace App\Controller\app;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(EntityManagerInterface $entityManager, Request $request, UserPasswordHasher $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));

            try {
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Youhou, toussa');
                return $this->redirectToRoute('app_home');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Ya une erreur en fait, wtf');
            }

        }

        return $this->render('user/register.html.twig', [
            'controller_name' => 'RegisterController',
            'form' => $form,
        ]);
    }
}
