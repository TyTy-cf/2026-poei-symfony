<?php

namespace App\Controller\admin;

use App\Entity\Category;
use App\Entity\Game;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Service\CategoryService;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/category')]
final class CategoryController extends AbstractController
{


    public function __construct(private readonly TranslatorInterface $translator,)
    {
    }

    #[Route('/index', name: 'admin_categories')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/show/{slug}', name: 'admin_category')]
    public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Category $category): Response
    {

        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/add', name: 'admin_add_category')]
    public function add(Request $request, CategoryService $categoryService): Response
    {
        return $this->handleForm($categoryService, new Category(), $request);
    }

    #[Route('/edit/{slug}', name: 'admin_edit_category')]
    public function edit(
        #[MapEntity(mapping: ['slug' => 'slug'])] Category $category,
        Request $request, CategoryService $categoryService): Response
    {
        return $this->handleForm($categoryService, $category, $request, true);
    }


    #[Route('/delete/{slug}', name: 'admin_delete_category')]
    public function delete(#[MapEntity(mapping: ['slug' => 'slug'])] Category $category, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($category);
        $entityManager->flush();

        return $this->redirectToRoute('admin_categories');
    }

    #[Route('/remove/{slugCategory}/{slugGame}', name: 'admin_remove_game_category')]
    public function removeGame(#[MapEntity(mapping: ['slugCategory' => 'slug'])] Category $category, #[MapEntity(mapping: ['slugGame' => 'slug'])] Game $game, EntityManagerInterface $entityManager): Response
    {
        $category->removeGame($game);
        $entityManager->flush();

        return $this->redirectToRoute('admin_category', ['slug' => $category->getSlug()]);
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

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/category/form.html.twig', [
            'form' => $form,
            'category' => $category,
            'isEdit' => $isEdit,
        ]);
    }
}
