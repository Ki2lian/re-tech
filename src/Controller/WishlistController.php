<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WishlistController extends AbstractController
{
    
    /**
     * @Route("/wishlist", name="wishlist")
     */
    public function index(): Response
    {
        $responseWishlist = $this->forward('App\Controller\ApiController::wishlist', [
            "id" => $this->container->get('security.token_storage')->getToken()->getUser()->getId(),
            'token' => $_ENV['API_TOKEN']
        ]);
        return $this->render('wishlist/index.html.twig', [
            "wishlist" => json_decode($responseWishlist->getContent(), true)
        ]);
    }
}
