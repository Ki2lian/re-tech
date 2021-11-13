<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Image;
use App\Form\AnnonceFormType;
use App\Form\ImageFormType;
use App\Repository\AnnonceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces-no-param")
     * @Route("/annonces/tag/{id}", name="annonces-tag")
     */
    public function annonces($id = 0): Response
    {
        return $this->render('annonce/annonces.html.twig', [
        ]);
    }

    // /**
    //  * @Route("/annonce/{id}", name="annonce-tag")
    //  */
    // public function annonce($id = 0): Response
    // {
    //     return $this->render('annonce/annonce.html.twig', [
    //     ]);
    // }

    /**
     * @Route("/annonce/editer/{id?}", name="annonceModif")
     */
    public function annonceModif(AnnonceRepository $rep, ? int $id,Request $req, EntityManagerInterface $em,  SluggerInterface $slugger): Response
    {
    
        $date = new DateTime();
        if ($id){
            $annonce = $rep->find($id);
            $form = $this->createForm(AnnonceFormType::class, $annonce);
        }else{
            $annonce = New Annonce;
            $user = $this->getUser();
            $form = $this->createForm(AnnonceFormType::class);    
        }
        $form->handleRequest($req);
    

        if($form->isSubmitted() && $form->isValid()){

            //PARTIE IMAGE 

             // On récupère les images transmises
            $images = $form->get('images')->getData();
    
            // On boucle sur les images
            foreach($images as $image){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
           
                // On crée l'image dans la base de données
                $img = new Image;
                $img->setPresentation(False)
                    ->setJeton('jeton')
                    ->setNom($fichier);
     
                $annonce->addImage($img);
            }

            //PARTIE ANNONCE FORM

            $annonce->setTitre(
                $form->get('titre')->getData()
            )
            ->setPrix(
                $form->get('prix')->getData()
            )
            ->setDescription(
                $form->get('description')->getData()
            );

            if(!$id){

                $annonce->setIdCompte($user)
                        ->setActif(1)
                        ->setDateCreation($date)
                        ->setAnnoncePayante(0);
                       
            }
         
            $annonce->setDateModification($date);
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute('user'); 


        }

        return $this->render('annonce/annonceModif.html.twig', [
            'controller_name' => 'AnnonceController',
            'formAnnonce'=> $form->createView(),
            'annonce'=>$annonce
        ]);
    }

     /**
     * @Route("/annonce{id}/{state}", name="annonceState")
     */
    public function annonceState(AnnonceRepository $rep,$state, $id,Request $req, EntityManagerInterface $em): Response
    {

        $annonce = $rep->find($id);
 
        if($state == "archiver"){
        $annonce->setActif(0);
        }else{
        $annonce->setActif(1);
        }

        $em->flush();
        
        return $this->redirectToRoute('user');                   
     
        
    return $this->render('user/modificationCompte.html.twig', [
        
    ]);
    }
    
    

    
}
