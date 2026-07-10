<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    #[Route('/game/{id}', name: 'app_game_show')]
    public function show(): Response
    {
        return $this->render('front/game/show.html.twig', [
            'controller_name' => 'GameController',
        ]);
    }
}
