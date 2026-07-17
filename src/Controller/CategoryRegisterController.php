<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CategoryRegisterController extends AbstractController
{
    #[Route('/category/register', name: 'app_category_register')]
    public function index(): Response
    {
        return $this->render('category_register/index.html.twig', [
            'controller_name' => 'CategoryRegisterController',
        ]);
    }


    #[Route('/{_locale}/category/new', name: 'app_register_categoryregister')]
    public function categoryRegister(
        EntityManagerInterface $em,
        Request                $request,
        TranslatorInterface $translator
    ): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            try{
                $em->persist($category);
                $em->flush();
                $this->addFlash("success", "bravo");
                return $this->redirectToRoute("app_home");

            }catch (\Exception $e){
                $this->addFlash('danger', $translator->trans('register.danger', [], 'alert'));
            }

        }


        return $this->render("front/category/category_register.html.twig", [
            "form" => $form,
        ]);

    }

    #[Route('/{_locale}/category/edit/{slug}', name: 'app_register_categoryedit')]
    public function categoryEdit(
        EntityManagerInterface $em,
        Request                $request,
        TranslatorInterface $translator
    ): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            try{
                $em->persist($category);
                $em->flush();
                $this->addFlash("success", "bravo");
                return $this->redirectToRoute("app_home");

            }catch (\Exception $e){
                $this->addFlash('danger', $translator->trans('register.danger', [], 'alert'));
            }

        }


        return $this->render("front/category/category_register.html.twig", [
            "form" => $form,
        ]);

    }
}
