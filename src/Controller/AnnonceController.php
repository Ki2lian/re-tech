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
     * @Route("/annonce", name="annonce")
     */
    public function index(): Response
    {
        return $this->render('annonce/index.html.twig', [
            'controller_name' => 'AnnonceController',
        ]);
    }


    /**
     * @Route("/annonceModif{id?}", name="annonceModif")
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
