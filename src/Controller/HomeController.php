<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(GameRepository $gameRepository, ReviewRepository $reviewRepository, CategoryRepository $categoryRepository): Response
    {
        $games = $gameRepository->findAll();
        $lastPublished = $gameRepository->findBy([],["publishedAt" => "DESC"], 9);
        $lastPublishedByPrice =  $gameRepository->findBy([],["price" => "DESC"], 9);
        $trustPilot = $reviewRepository->findBy(["rating" => 5], ["createdAt" => "DESC"], 5);
        $gamesDCBA = $gameRepository->findBy([], ["name" => "DESC"], 6);
        $categoriesABCD = $categoryRepository->findBy([], ["name" => "ASC"], 9);

        return $this->render('front/home/index.html.twig', [
            'games' => $games,
            "lastPublished" => $lastPublished,
            "lastPublishedByPrice" => $lastPublishedByPrice,
            "trustPilot" => $trustPilot,
            "gamesDCBA" => $gamesDCBA,
            "categoriesABCD" => $categoriesABCD,

        ]);
    }
}
