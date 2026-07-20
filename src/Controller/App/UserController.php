<?php

namespace App\Controller\App;

use App\Form\RegisterType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserController extends AbstractController
{
    #[Route('/{_locale}/user/{name}', name: 'app_user_show')]
    public function index(
        UserRepository $userRepository,
        TranslatorInterface $translator,
        Request        $request,
        string         $name,
    ): Response
    {
        $user = $userRepository->findFullBy($name);
        if ($user === null) {
            $this->addFlash('error', $translator->trans('user.not_found', [], 'alert'));
            return $this->redirectToRoute('app_home');
        }
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
