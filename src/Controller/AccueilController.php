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
        $wishlist = '';
        $responseAnnonces = $this->forward('App\Controller\ApiController::allAnnoncesPaid', [
            'token' => $_ENV['API_TOKEN']
        ]);
        $annonces = json_decode($responseAnnonces->getContent(), true);

        // If the user is connected
        $securityContext = $this->container->get('security.authorization_checker');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $responseWishlist = $this->forward('App\Controller\ApiController::wishlist', [
                "id" => $this->getUser()->getId(),
                'token' => $_ENV['API_TOKEN']
            ]);
            $wishlist = json_decode($responseWishlist->getContent(), true);
        }

        return $this->render('accueil/index.html.twig', [
            'annonces' => $annonces,
            'wishlist' => $wishlist
        ]);

    }
}


?>
