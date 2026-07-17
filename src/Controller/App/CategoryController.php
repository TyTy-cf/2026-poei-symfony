<?php

namespace App\Controller\App;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/category/', name: 'app_category_')]
final class CategoryController extends AbstractController
{

    #[Route(name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('front/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('{slug}', name: 'show')]
    public function new(): Response
    {
        return $this->render('front/category/show.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

}
