<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Service\CategoryService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/admin/category/', name: 'admin_category_')]
final class CategoryController extends AbstractController
{

    #[Route('new', name: 'new')]
    public function new(
        CategoryService $categoryService,
        Request         $request
    ): Response
    {
        return $this->handleForm($categoryService, new Category(), $request);
    }

    #[Route('edit/{slug}', name: 'edit')]
    public function edit(
        #[MapEntity(mapping: ['slug' => 'slug'])] Category $category,
        CategoryService                                    $categoryService,
        Request                                            $request
    ): Response
    {
        return $this->handleForm($categoryService, $category, $request, true);
    }

    private function handleForm(
        CategoryService $categoryService,
        Category        $category,
        Request         $request,
        bool            $isEdit = false
    ): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($categoryService->persistCategory($category)) {
                if ($isEdit) {
                    $this->addFlash('success', 'category.updated');
                } else {
                    $this->addFlash('success', 'category.created');
                }
            } else {
                $this->addFlash('danger', 'category.error');
            }

            return $this->redirectToRoute('admin_category_new');
        }

        return $this->render('admin/category/form.html.twig', [
            'form' => $form,
            'category' => $category,
            'isEdit' => $isEdit,
        ]);
    }

}
