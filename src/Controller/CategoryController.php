<?php

namespace App\Controller;

use App\Entity\Category;

use App\Repository\CategoryRepository;
use App\Form\CategoryType;
use App\Service\CategoryFormService;
use App\Service\SlugifyService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Attribute\Route;


final class CategoryController extends AbstractController
{
  #[Route('{_locale}/category/{name}', name: 'app_category_show')]
  public function index(Request $request, CategoryRepository $categoryRepository, string $name): Response
  {
    $category = $categoryRepository->FindAllGamesInCategory(['name' => $name]);

    // dd($category);

    if (!$category) {
      $this->addFlash(
        'danger',
        'category not found.'
      );
      return $this->redirectToRoute('app_home', [
        '_locale' => $request->getDefaultLocale(),
      ]);
    }


    return $this->render('front/category/show.html.twig', [
      'controller_name' => 'categoryController',
      'category' => $category,
    ]);
  }

  #[Route('{_locale}/category/add', name: 'app_category_add')]
  public function add(Request $request, EntityManagerInterface $em, SlugifyService $slugifyService, CategoryFormService $categoryFormService): Response
  {

    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {
      try {
        $categoryFormService->save($category, $em, $slugifyService);
        $this->addFlash(
          'success',
          'category created successfully.'
        );

        return $this->redirectToRoute('app_home');
      } catch (\Exception $e) {
        $this->addFlash(
          'danger',
          'An error occurred while creating the category.'
        );
      }
    }


    return $this->render('front/category/register.html.twig', [
      'controller_name' => 'RegisterController',
      'form' => $form,
    ]);
  }

  #[Route('{_locale}/category/edit/{id}', name: 'app_category_edit')]
  public function edit(Request $request, EntityManagerInterface $em, SlugifyService $slugifyService, CategoryFormService $categoryFormService, Category $category): Response
  {
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {
      try {
        $categoryFormService->save($category, $em, $slugifyService);
        $this->addFlash(
          'success',
          'category updated successfully.'
        );

        return $this->redirectToRoute('app_home');
      } catch (\Exception $e) {
        $this->addFlash(
          'danger',
          'An error occurred while updating the category.'
        );
      }
    }


    return $this->render('front/category/register.html.twig', [
      'controller_name' => 'RegisterController',
      'form' => $form,
    ]);
  }
}
