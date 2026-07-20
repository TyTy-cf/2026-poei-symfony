<?php

namespace App\Controller\Admin;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use App\Slugify\SlugService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/{_locale}/admin/country', name: 'admin_country_')]
final class CountryController extends AbstractController
{

    #[Route(name: 'index', methods: ['GET'])]
    public function index(CountryRepository $countryRepository): Response
    {
        return $this->render('admin/country/index.html.twig', [
            'countries' => $countryRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        SlugService            $slugService,
        EntityManagerInterface $entityManager
    ): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $country->setSlug($slugService->slugify($country->getName()));
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

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Country $country): Response
    {
        return $this->render('admin/country/show.html.twig', [
            'country' => $country,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                $request,
        SlugService            $slugService,
        Country                $country,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $country->setSlug($slugService->slugify($country->getName()));
            $country->setUrlFlag('https://flagcdn.com/32x24/' . $country->getCode() . '.png');
            $entityManager->flush();

            return $this->redirectToRoute('admin_country_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/country/edit.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Country $country, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $country->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($country);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_country_index', [], Response::HTTP_SEE_OTHER);
    }
}
