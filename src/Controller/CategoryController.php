<?php

namespace App\Controller;


use App\Repository\CategoryRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
  #[Route('/category/{name}', name: 'app_category_show')]
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


    return $this->render('category/show.html.twig', [
      'controller_name' => 'categoryController',
      'category' => $category,
    ]);
  }
}
