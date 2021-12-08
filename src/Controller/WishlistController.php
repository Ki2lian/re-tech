<?php

namespace App\Controller;

use App\Entity\Wishlist;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/wishlist")
 */
class WishlistController extends AbstractController
{
    
    /**
     * @Route("/", name="wishlist")
     */
    public function index(): Response
    {
        $responseWishlist = $this->forward('App\Controller\ApiController::wishlist', [
            "id" => $this->getUser()->getId(),
            'token' => $_ENV['API_TOKEN']
        ]);
        return $this->render('wishlist/index.html.twig', [
            "wishlist" => json_decode($responseWishlist->getContent(), true)
        ]);
    }

    /**
     * @Route("/toggle", name="wishlist_toggler")
     */
    public function toggleWishlist(Request $request, AnnonceRepository $ar, EntityManagerInterface $entityManager): Response
    {
        $idAnnonce = $request->get('idAnnonce');
        if(intval($idAnnonce) == 0) return new Response('', 404);
        $isAjax = $request->isXMLHttpRequest();
        if (!$isAjax) return new Response('', 404);
        $annonce = $ar->findOneBy(array('id' => $idAnnonce));
        if($annonce === null || $this->getUser() === null ) return new Response('', 404);
        
        if($annonce->getIdCompte()->getId() == $this->getUser()->getId()){
            return $this->json(["code" => 403, "message" => "Vous ne pouvez pas ajouté votre annonce dans votre wishlist"],200);
        }
        foreach ($annonce->getWishlists() as $key => $value) {
            if($value->getIdCompte()->getId() === $this->getUser()->getId()){
                $annonce->removeWishlist($value);
                $entityManager->flush();
                return $this->json(["code" => 200, "message" => "Annonce retirée de la wishlist"],200);
                break;
            }
        }
        $wishlist = new Wishlist();
        $wishlist->setIdCompte($this->getUser());
        $wishlist->setIdAnnonce($annonce);
        $wishlist->setDateCreation(new \DateTime());
        $wishlist->setDateModifAnnonce($annonce->getDateModification());
        $entityManager->persist($wishlist);
        $entityManager->flush();
        return $this->json(["code" => 200, "message" => "Annonce ajoutée à la wishlist"],200);
    }
}
