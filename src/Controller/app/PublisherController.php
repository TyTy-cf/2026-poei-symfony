<?php

namespace App\Controller\app;

use App\Repository\GameRepository;
use App\Repository\PublisherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PublisherController extends AbstractController
{
    #[Route('/publisher/{slug}', name: 'app_publisher')]
    public function index(string $slug, GameRepository $gameRepository, PublisherRepository $publisherRepository): Response
    {
        $publisher = $publisherRepository->findOneBy(['slug' => $slug]);

        $gamesPublisher = $gameRepository->findBy(['publisher' => $publisher], []);

        return $this->render('front/publisher/index.html.twig', [
            'controller_name' => 'PublisherController',
            'gamesPublisher' => $gamesPublisher,
            'publisher' => $publisher,
        ]);
    }
}
