<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findAll();

        return $this->render('front/home/index.html.twig', [
            'games' => $games,
        ]);
    }
}
