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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces/{id}", name="annonces")
     * @Route("/annonces/tag/{nom}", name="annonces-tag")
     */
    public function annonces($nom = 0, $id = 0): Response
    {
        if($id == 0) $id = 1;
        if($id == 1){
            $skip = 0;
            $fetch = 12;
        }else{
            $skip = 12 * ($id-1);
            $fetch = 12;
        }

        $wishlist = '';
        if($nom == 0){
            $responseAnnonces = $this->forward('App\Controller\ApiController::allAnnonces', [
                'token' => $_ENV['API_TOKEN'],
                'skip' => $skip,
                'fetch' => $fetch
            ]);
            $annonces = json_decode($responseAnnonces->getContent(), true);
            if(json_decode($responseAnnonces->getContent(), true) == []) $id -= 1;
        }else{
            $responseAnnonces = $this->forward('App\Controller\ApiController::singleTag', [
                'token' => $_ENV['API_TOKEN'],
                'nom' => $nom
            ]);
            if(json_decode($responseAnnonces->getContent(), true) != []){
                $annonces = json_decode($responseAnnonces->getContent(), true)[0]['annonces'];
                krsort($annonces);
                $annonces = array_values($annonces);
    
                foreach($annonces as $key=>$value) if(!$value['actif']) unset($annonces[$key]);
    
                $annonces = array_values($annonces);
            }else{
                return $this->redirectToRoute('annonces');
            }
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
            'annonces' => $annonces,
            'wishlist' => $wishlist,
            'id' => $id
        ]);
    }

    



    /**
     * @Route("/annonce/editer/{id}", name="annonceModif")
     * @Route("/annonce/editer", name="annonceModifWithoutId")
     */
    public function annonceModif(? Annonce $annonce,int $id = 0,Request $req, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $date = new DateTime();
        if ($id != 0){
            if(isset($annonce) && $annonce->getIdCompte()->getId() === $this->getUser()->getId()){
                $form = $this->createForm(AnnonceFormType::class, $annonce);
            }else{
                return $this->redirectToRoute('user');
            }
        }else{
            $annonce = New Annonce;    
            $form = $this->createForm(AnnonceFormType::class);    
        }
        $form->handleRequest($req);
    

        if($form->isSubmitted() && $form->isValid()){

            //PARTIE IMAGE 
         

             // On récupère les images transmises
            $images = $form->get('images')->getData();
            //On vient chercher le 1er index pour le mettre en img de présentation
            $count = 1;
            $length = count($images);
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
                if($count === 1){
                    $img->setPresentation(True);
                }
     
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
            'formAnnonce'=> $form->createView(),
            'annonce'=>$annonce
        ]);
    }

     /**
     * @Route("/annonce/{id}/{state}", name="annonceState")
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
    }
    /**
     * @Route("/supprime/image/{id}", name="annonces_delete_image")
    */

    public function deleteImage(Image $image, Request $request){

        $data = json_decode($request->getContent(), true);

    
            // On récupère le nom de l'image
            $nom = $image->getNom();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        
    }

    /**
     * @Route("/annonce/{id}", name="annonce-single")
     */
    public function annonce($id = 0): Response
    {
        $wishlist = '';
        $responseAnnonce = $this->forward('App\Controller\ApiController::singleAnnonce', [
            'token' => $_ENV['API_TOKEN'],
            'id' => $id
        ]);

        // If the user is connected
        $securityContext = $this->container->get('security.authorization_checker');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $responseWishlist = $this->forward('App\Controller\ApiController::wishlist', [
                "id" => $this->container->get('security.token_storage')->getToken()->getUser()->getId(),
                'token' => $_ENV['API_TOKEN']
            ]);
            $wishlist = json_decode($responseWishlist->getContent(), true);
        }

        return $this->render('annonce/annonce.html.twig', [
            'annonce' => json_decode($responseAnnonce->getContent(), true),
            'wishlist' => $wishlist
        ]);
    }
    

    
}
