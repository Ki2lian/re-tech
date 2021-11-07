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
            'token' => $_ENV['API_TOKEN'],
        ]);

        return $this->render('admin/annonces.html.twig', [
            'annonces' => json_decode($response->getContent(), true)
        ]);
    }

    #[Route('/admin/annonce/{id}', name: 'adminAnnonceDetail')]
    public function annonceDetail($id = 0): Response
    {
        if($id == 0) return $this->redirectToRoute('adminAnnonceDetail', array('id' => 1));

        $response = $this->forward('App\Controller\ApiController::singleAnnonce', [
            'token' => $_ENV['API_TOKEN'],
            'id' => $id
        ]);

        $annonce = json_decode($response->getContent(), true);
        if(isset($annonce['code']) && $annonce['code'] == 404) return $this->redirectToRoute('adminHome');

        return $this->render('admin/annonce_detail.html.twig', [
            'annonce' => $annonce
        ]);
    }

    #[Route('/admin/utilisateurs', name: 'adminUtilisateurs')]
    public function utilisateurs(): Response
    {
        $response = $this->forward('App\Controller\ApiController::adminAllUsers', [
            'token' => $_ENV['API_TOKEN'],
        ]);

        return $this->render('admin/utilisateurs.html.twig', [
            'users' => json_decode($response->getContent(), true)
        ]);
    }

    #[Route('/admin/utilisateur/{id}', name: 'adminUtilisateurDetail')]
    public function utilisateurDetail($id = 0): Response
    {
        if($id == 0) return $this->redirectToRoute('adminUtilisateurDetail', array('id' => 1));

        $response = $this->forward('App\Controller\ApiController::singleUser', [
            'token' => $_ENV['API_TOKEN'],
            'id' => $id
        ]);

        $user = json_decode($response->getContent(), true);
        if(isset($user['code']) && $user['code'] == 404) return $this->redirectToRoute('adminHome');

        return $this->render('admin/utilisateur_detail.html.twig', [
            'user' => $user
        ]);
    }
    
}
