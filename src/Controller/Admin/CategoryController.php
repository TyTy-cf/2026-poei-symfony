<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\RegisterType;
use App\Service\SlugifyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/admin/category/', name: 'admin_category_')]
final class CategoryController extends AbstractController
{

    #[Route('edit/{slug}', name: 'edit')]
    public function edit(): Response
    {
        return $this->render('admin/category/new.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('new', name: 'new')]
    public function new(
        Request                $request,
        EntityManagerInterface $em,
        SlugifyService         $slugifyService,
    ): Response
    {
        $category = new Category();
        $form = $this->createForm(RegisterType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->getData()->getName();
            $category->setSlug($slugifyService->slugify($name));
            try {
                $em->persist($category);
                $em->flush();
                $this->addFlash("success", "c'est ajouté en base");
            } catch (\Exception $e) {
                $this->addFlash("error", "et bah non");
            }
        }
        return $this->render('admin/category/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
