<?php

namespace App\Controller\Admin;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use App\Slugify\SlugService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/admin/publisher', name: 'admin_publisher_')]
final class PublisherController extends AbstractController
{

    #[Route(name: 'index', methods: ['GET'])]
    public function index(PublisherRepository $publisherRepository): Response
    {
        return $this->render('admin/publisher/index.html.twig', [
            'publishers' => $publisherRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        SlugService            $slugService,
        EntityManagerInterface $entityManager
    ): Response
    {
        $publisher = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisher->setSlug($slugService->slugify($publisher->getName()));
            foreach ($publisher->getGames() as $game) {
                $game->setPublisher($publisher);
            }
            $entityManager->persist($publisher);
            $entityManager->flush();

            return $this->redirectToRoute('admin_publisher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/publisher/new.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Publisher $publisher): Response
    {
        return $this->render('admin/publisher/show.html.twig', [
            'publisher' => $publisher,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                $request,
        Publisher              $publisher,
        SlugService            $slugService,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisher->setSlug($slugService->slugify($publisher->getName()));
            foreach ($publisher->getGames() as $game) {
                $game->setPublisher($publisher);
            }
            $entityManager->flush();

            return $this->redirectToRoute('admin_publisher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/publisher/edit.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Publisher $publisher, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $publisher->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($publisher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_publisher_index', [], Response::HTTP_SEE_OTHER);
    }
}
