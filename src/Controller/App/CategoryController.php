<?php

namespace App\Controller\App;

use App\Repository\CategoryRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/category/', name: 'app_category_')]
final class CategoryController extends AbstractController
{
  #[Route('{name}', name: 'show')]
  public function show(Request $request, CategoryRepository $categoryRepository, string $name): Response
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
}
