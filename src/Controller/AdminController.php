<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'adminHome')]
    public function index(): Response
    {

        return $this->render('admin/index.html.twig', [
        ]);
    }

    #[Route('/admin/annonces', name: 'adminAnnonces')]
    public function annonces(): Response
    {
        $response = $this->forward('App\Controller\ApiController::adminAllAnnonces', [
            'token' => "azerty",
        ]);

        return $this->render('admin/annonces.html.twig', [
            'annonces' => json_decode($response->getContent(), true)
        ]);
    }

    #[Route('/admin/annonce/{id}', name: 'adminAnnonceDetail')]
    #[Route('/admin/annonce/', name: 'adminAnnonceDetail-noparam')]
    public function annonceDetail($id): Response
    {
        // $response = $this->forward('App\Controller\ApiController::adminAllAnnonces', [
        //     'token' => "azerty",
        // ]);

        return $this->render('admin/annonce_detail.html.twig', [
            // 'annonces' => json_decode($response->getContent(), true)
        ]);
    }
    
}
