<?php

namespace App\Controller\Admin;

use App\Entity\Category;

use App\Form\CategoryType;
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





  #[Route('edit/{id}', name: 'edit')]
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
