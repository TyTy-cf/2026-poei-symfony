<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;
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
    public function edit(
        Request                $request,
        EntityManagerInterface $em,
        SlugifyService         $slugifyService,
        CategoryRepository      $categoryRepository,
        string $slug,
//        Category                $categoryEdit
    ): Response
    {
        $categoryEdit = $categoryRepository->findOneFullBy($slug);
        $form = $this->createForm(CategoryType::class, $categoryEdit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->getData()->getName();
            $categoryEdit->setSlug($slugifyService->slugify($name));
            foreach ($categoryEdit->getGames() as $game) {
                $game->addCategory($categoryEdit);
            }
            try {
                $em->persist($categoryEdit);
                $em->flush();
                $this->addFlash("success", "c'est modifié en base");
            } catch (\Exception $e) {
                $this->addFlash("error", "et bah non");
            }
        }
        return $this->render('admin/category/edit.html.twig', [
            'category' => $categoryEdit,
            'form' => $form,
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
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->getData()->getName();
            $category->setSlug($slugifyService->slugify($name));
            foreach ($category->getGames() as $game) {
                $game->addCategory($category);
            }
            try {
                $em->persist($category);
                $em->flush();
                $this->addFlash("success", "c'est ajouté en base");
            } catch (\Exception $e) {
                $this->addFlash("error", "et bah non");
            }
        }
        return $this->render('admin/category/new.html.twig', [
            'form' => $form,
        ]);
    }
}
