<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/admin/category/', name: 'admin_category_')]
final class CategoryController extends AbstractController
{

    #[Route('edit/{slug}', name: 'edit')]
    public function edit(): Response
    {
        return $this->render('admin/category/new.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('new', name: 'new')]
    public function new(): Response
    {
        return $this->render('admin/category/edit.html.twig', [
            'form' => $form,
        ]);
    }

}
