<?php

namespace App\Controller\admin;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/publisher')]
final class PublisherController extends AbstractController
{
    #[Route(name: 'admin_publisher_index', methods: ['GET'])]
    public function index(PublisherRepository $publisherRepository): Response
    {
        return $this->render('admin/publisher/index.html.twig', [
            'publishers' => $publisherRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_publisher_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Slugger $slugger): Response
    {
        $publisher = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $publisher->setSlug($slugger->slugify($publisher->getName()));
            $entityManager->persist($publisher);
            $entityManager->flush();

            return $this->redirectToRoute('admin_publisher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/publisher/new.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'admin_publisher_show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Publisher $publisher): Response
    {
        return $this->render('admin/publisher/show.html.twig', [
            'publisher' => $publisher,
        ]);
    }

    #[Route('/{slug}/edit', name: 'admin_publisher_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity(mapping: ['slug' => 'slug'])] Publisher $publisher, EntityManagerInterface $entityManager, Slugger $slugger): Response
    {
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisher->setSlug($slugger->slugify($publisher->getName()));
            $entityManager->flush();

            return $this->redirectToRoute('admin_publisher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/publisher/edit.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'admin_publisher_delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity(mapping: ['slug' => 'slug'])] Publisher $publisher, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publisher->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($publisher);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_publisher_index', [], Response::HTTP_SEE_OTHER);
    }
}
