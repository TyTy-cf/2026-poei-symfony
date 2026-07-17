<?php

namespace App\Controller;

use App\Repository\PublisherRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PublisherController extends AbstractController
{
  #[Route('/publisher/{slug}', name: 'app_publisher_show')]
  public function show(PublisherRepository $publisherRepository, string $slug): Response
  {

    if (!$slug || $slug === 'publisher') {
      return $this->redirectToRoute('app_home');
    }

    $publisher = $publisherRepository->getPublisherBySlug($slug);

    return $this->render('front/publisher/show.html.twig', [
      'controller_name' => 'PublisherController',
      'publisher' => $publisher,
    ]);
  }
}
