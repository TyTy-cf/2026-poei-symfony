<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{

    #[Route('/{_locale}/admin', name: 'admin_home')]
    public function index(): Response
    {
        
        return $this->render('admin/home/index.html.twig', [
        ]);
    }

}
