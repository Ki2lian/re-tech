<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceFormType;
use App\Repository\AnnonceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces-no-param")
     * @Route("/annonces/tag/{id}", name="annonces-tag")
     */
    public function annonces($listId = 0): Response
    {
        $wishlist = '';
        if($listId == 0){
            $responseAnnonces = $this->forward('App\Controller\ApiController::allAnnonces', [
                'token' => $_ENV['API_TOKEN'],
                'skip' => 0,
                'fetch' => 12
            ]);
        }else{
            $responseAnnonces = $this->forward('App\Controller\ApiController::annoncesByTag', [
                'token' => $_ENV['API_TOKEN'],
                'listId' => $listId,
                'skip' => 0,
                'fetch' => 12
            ]);
        }
        // If the user is connected
        $securityContext = $this->container->get('security.authorization_checker');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $responseWishlist = $this->forward('App\Controller\ApiController::wishlist', [
                "id" => $this->container->get('security.token_storage')->getToken()->getUser()->getId(),
                'token' => $_ENV['API_TOKEN']
            ]);
            $wishlist = json_decode($responseWishlist->getContent(), true);
        }

        return $this->render('annonce/annonces.html.twig', [
            'annonces' => json_decode($responseAnnonces->getContent(), true),
            'wishlist' => $wishlist
        ]);
    }

    /**
     * @Route("/annonce/{id}", name="annonce-tag")
     */
    public function annonce($id = 0): Response
    {
        $response = $this->forward('App\Controller\ApiController::singleAnnonce', [
            'token' => $_ENV['API_TOKEN'],
            'id' => $id
        ]);

        return $this->render('annonce/annonce.html.twig', [
        ]);
    }



    /**
     * @Route("/annonce/edit/{id?}", name="annonceModif")
     */
    public function annonceModif(AnnonceRepository $rep, $id,Request $req, EntityManagerInterface $em): Response
    {
        $date = new DateTime();
        if ($id){
            $annonce = $rep->find($id);
            $form = $this->createForm(AnnonceFormType::class, $annonce);
        }else{
            $user = $this->getUser();
            $annonce = New Annonce;
            $form = $this->createForm(AnnonceFormType::class);
        }
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){

            $annonce->setTitre(
                $form->get('titre')->getData()
            );
            $annonce->setPrix(
                $form->get('prix')->getData()
            );
            $annonce->setDescription(
                $form->get('description')->getData()
            );

            if(!$id){

                $annonce->setIdCompte($user);
                $annonce->setActif(1);
                $annonce->setDateCreation($date);
                $annonce ->setAnnoncePayante(0);
            
            }

            $annonce->setDateModification($date);

            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('user'); 


        }

        return $this->render('annonce/index.html.twig', [
            'controller_name' => 'AnnonceController',
            'formAnnonce'=> $form->createView(),
            'annonce'=>$annonce
        ]);
    }


    
}
