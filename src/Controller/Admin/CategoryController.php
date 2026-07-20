<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\CategoryFormService;
use App\Service\SlugifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/admin/category/', name: 'admin_category_')]
final class CategoryController extends AbstractController
{
  #[Route('add', name: 'add')]
  public function add(
    Request $request,
    EntityManagerInterface $em,
    SlugifyService $slugifyService,
    CategoryFormService $categoryFormService
  ): Response {
    $category = new Category();

    return $this->handleFormSubmission(
      $request,
      $em,
      $slugifyService,
      $categoryFormService,
      $category,
      false
    );
  }

  #[Route('edit/{id}', name: 'edit')]
  public function edit(
    Request $request,
    EntityManagerInterface $em,
    SlugifyService $slugifyService,
    CategoryFormService $categoryFormService,
    Category $category
  ): Response {
    return $this->handleFormSubmission(
      $request,
      $em,
      $slugifyService,
      $categoryFormService,
      $category,
      true
    );
  }

  #[Route('delete/{id}', name: 'delete')]
  public function delete(
    Category $category
  ): Response {

    $categoryName = $category->getName();

    die($categoryName);

    return $this->render('admin/category/index.html.twig', [
      'controller_name' => 'HomeController',
    ]);
  }

  #[Route('/', name: 'index')]
  public function index(CategoryRepository $categoryRepository): Response
  {

    $categories = $categoryRepository->findAllCategories();


    return $this->render('admin/category/index.html.twig', [
      'controller_name' => 'HomeController',
      'categories' => $categories,
    ]);
  }

  #[Route('/show/{id}', name: 'show')]
  public function show(Category $category): Response
  {

    return $this->render('admin/category/show.html.twig', [
      'controller_name' => 'HomeController',
      'category' => $category,
    ]);
  }



  private function handleFormSubmission(
    Request $request,
    EntityManagerInterface $em,
    SlugifyService $slugifyService,
    CategoryFormService $categoryFormService,
    Category $category,
    bool $isEdit = false
  ): Response {
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      try {
        $categoryFormService->save($category, $em, $slugifyService);

        $this->addFlash(
          'success',
          $isEdit ? 'Category updated successfully.' : 'Category created successfully.'
        );

        return $this->redirectToRoute('app_home');
      } catch (\Exception $e) {
        $this->addFlash('danger', 'An error occurred while saving the category.');
        // optionally log $e
      }
    }

    return $this->render('front/category/register.html.twig', [
      'controller_name' => 'CategoryController',
      'form' => $form->createView(),
    ]);
  }
}
