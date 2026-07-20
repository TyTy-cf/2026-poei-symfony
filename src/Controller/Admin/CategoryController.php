<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/{_locale}/admin/category/', name: 'admin_category_')]
final class CategoryController extends AbstractController
{


    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    #[Route('', name: 'index_category')]
    public function categories(
        CategoryRepository $categoryRepository
    ): Response
    {
        $categories = $categoryRepository->findBy([], ['name' => 'ASC']);

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('{slug}', name: '_show')]
    public function show(
        CategoryRepository $categoryRepository,
        ?string $slug
    ): Response
    {
        $category = $categoryRepository->findAllWithGamesOrdered($slug);



        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
        ]);
    }

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

    #[Route('delete/{slug}', name: 'delete')]
    public function delete(Category $category, EntityManagerInterface $em): void
    {
            $this->em->remove($category);
            $this->em->flush();
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
                    $this->addFlash('success', $this->translator->trans('admin.category.updated', [], 'alert'));
                } else {
                    $this->addFlash('success', $this->translator->trans('admin.category.created', [], 'alert'));
                }
            } else {
                $this->addFlash('danger', $this->translator->trans('admin.error', [], 'alert'));
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
