<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PublisherController extends AbstractController
{
    #[Route('/publisher', name: 'app_publisher')]
    public function index(): Response
    {
        return $this->render('publisher/index.html.twig', [
            'controller_name' => 'PublisherController',
        ]);
    }
}
