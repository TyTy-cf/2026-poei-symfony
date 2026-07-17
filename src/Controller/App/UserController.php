<?php

namespace App\Controller\App;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserController extends AbstractController
{
    #[Route('/{_locale}/user/{name}', name: 'app_user_show')]
    public function index(
        UserRepository         $userRepository,
        EntityManagerInterface $em,
        TranslatorInterface    $translator,
        Request                $request,
        string                 $name,
    ): Response
    {
        $user = $userRepository->findFullBy($name);
        $isMe = false;
        $loggedUser = $this->getUser();

        if ($loggedUser !== null && $user->getEmail() === $loggedUser->getUserIdentifier()) {
            $isMe = true;
        }

        $form = null;
        if ($isMe) {
            // Ajouter un form d'édition de profil
            $form = $this->createForm(UserType::class, $user, [
                'is_edit' => true,
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $em->flush();
                    $this->addFlash('success', $translator->trans('user.edit.success', [], 'alert'));
                } catch (\Exception $e) {
                    $this->addFlash('danger', $translator->trans('user.edit.danger', [], 'alert'));
                }
            }
        }

        return $this->render('front/user/show.html.twig', [
            'user' => $user,
            'isMe' => $isMe,
            'form' => $form,
        ]);
    }

}
