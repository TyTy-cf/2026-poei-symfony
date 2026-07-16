<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class RegisterController extends AbstractController
{
    #[Route('/{_locale}/register', name: 'app_register')]
    public function register(
        EntityManagerInterface $em,
        TranslatorInterface    $translator,
        Request                $request
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new DateTimeImmutable());

            try {
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', $translator->trans('register.success', [], 'alert'));
                return $this->redirectToRoute('app_home');
            } catch (\Exception $e) {
                $this->addFlash('danger', $translator->trans('register.danger', [], 'alert'));
                dump($e->getMessage());
            }
        }

        return $this->render('front/user/register.html.twig', [
            'form' => $form,
        ]);
    }
}
