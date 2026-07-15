<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home_redirect')]
    public function homeRedirect(Request $request): Response
    {
        $locales = $request->getLocale();
        return $this->redirectToRoute('app_home', [
            '_locale' => explode('_', $locales)[0],
        ]);
    }

    #[Route('/{_locale}/', name: 'app_home')]
    public function home(
        GameRepository     $gameRepository,
        ReviewRepository   $reviewRepository,
        CategoryRepository $categoryRepository,
    ): Response
    {
        $trends = $gameRepository->findByGameTimeSum(9);
        $bests = $gameRepository->findBy([], ['publishedAt' => 'DESC'], 9);
        $reviews = $reviewRepository->findFullByRatingMax(5);
        $tops = $gameRepository->findByBestRating(6);
        $categories = $categoryRepository->findBy([], ['name' => 'ASC'], 9);

        return $this->render('front/home/index.html.twig', [
            'trends' => $trends,
            'bests' => $bests,
            'reviews' => $reviews,
            'tops' => $tops,
            'categories' => $categories,
        ]);
    }
}
