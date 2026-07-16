<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\SlugService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CategoryController extends AbstractController
{
    #[Route('/{_locale}/category/{slug}', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository,
                          TranslatorInterface $translator,
                          ?string $slug): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        if ($category === null) {
            $this->addFlash('danger', $translator->trans('category.not_found', [], 'alert'));
            return $this->redirectToRoute('app_home');
        }

         return $this->render('front/category/show.html.twig', [
             'category' => $category,
         ]);
    }

    #[Route('/{_locale}/new-category', name: 'app_new_category')]
    public function categoryNew(EntityManagerInterface $em,
                         TranslatorInterface    $translator,
                         Request                $request, SlugService $service): Response
    {
        $category = new Category();
        $formNewCategory = $this->createForm(CategoryType::class, $category);
        $formNewCategory->handleRequest($request);

        if ($formNewCategory->isSubmitted() && $formNewCategory->isValid()) {
            $category->setSlug($service->slugify($category->getName()));

            try {
                $em->persist($category);
                $em->flush();
                $this->addFlash('success', $translator->trans('category.success', [], 'alert'));
                return $this->redirectToRoute('app_home');
            } catch (\Exception $e) {
                $this->addFlash('danger', $translator->trans('category.danger', [], 'alert'));
                dump($e->getMessage());
            }
        }

        return $this->render('front/category/cateform.html.twig', [
            'formNewCategory' => $formNewCategory,
        ]);

        dump($formNewCategory);
    }
}

