<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/{_locale}/category/{slug}', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('front/category/show.html.twig' [

        ]);
    }
}
