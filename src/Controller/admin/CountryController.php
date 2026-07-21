<?php

namespace App\Controller\admin;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/country')]
final class CountryController extends AbstractController
{
    #[Route(name: 'admin_country_index', methods: ['GET'])]
    public function index(CountryRepository $countryRepository): Response
    {
        return $this->render('admin/country/index.html.twig', [
            'countries' => $countryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_country_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Slugger $slugger): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $country->setSlug($slugger->slugify($country->getName()));
            $country->setUrlFlag('https://flagcdn.com/32x24/' . $country->getCode() . '.png');
            $entityManager->persist($country);
            $entityManager->flush();

            return $this->redirectToRoute('admin_country_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/country/new.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'admin_country_show', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['slug' => 'slug'])] Country $country): Response
    {
        return $this->render('admin/country/show.html.twig', [
            'country' => $country,
        ]);
    }

    #[Route('/{slug}/edit', name: 'admin_country_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity(mapping: ['slug' => 'slug'])] Country $country, EntityManagerInterface $entityManager, Slugger $slugger): Response
    {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $country->setSlug($slugger->slugify($country->getName()));
            $country->setUrlFlag('https://flagcdn.com/32x24/' . $country->getCode() . '.png');
            $entityManager->flush();

            return $this->redirectToRoute('admin_country_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/country/edit.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'admin_country_delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity(mapping: ['slug' => 'slug'])] Country $country, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$country->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($country);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_country_index', [], Response::HTTP_SEE_OTHER);
    }
}
