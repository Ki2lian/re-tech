<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(EntityManagerInterface $em): Response
    {

        // $response = $this->forward('App\Controller\ApiController::allUsers', [
        //     'token' => $_ENV['API_TOKEN'],
        //     // 'fetch' => 20
        // ]);
        return $this->render('accueil/index.html.twig', [
            // 'controller_name' => 'AccueilController',
            // 'users' => json_decode($response->getContent(), true)
        ]);

    }
}


?>
