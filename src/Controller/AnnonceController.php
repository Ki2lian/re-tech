<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Image;
use App\Entity\Tag;
use App\Form\AnnonceFormType;
use App\Form\ImageFormType;
use App\Repository\AnnonceRepository;
use App\Repository\TagRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/annonces/{id}", name="annonces")
     * @Route("/annonces/tag/{nom}/{id}", name="annonces-tag")
     */
    public function annonces($nom = '', $id = 0, AnnonceRepository $ar, Request $request): Response
    {
        // $price = null;
        // if($request->getMethod() == "POST"){
        //     $filter = json_decode($request->get('filter'), true);
        //     $price = $filter["price"];
        // }

        $fetch = 12;
        if($id == 0) $id = 1;
        if($id == 1){
            $skip = 0;
        }else{
            $skip = 12 * ($id-1);
        }
        // $fetch = 1;
        // $skip = 1* ($id-1);

        $wishlist = '';
        if (empty($nom)) {
            $responseAnnonces = $this->forward('App\Controller\ApiController::allAnnonces', [
                'token' => $_ENV['API_TOKEN'],
                'skip' => $skip,
                'fetch' => $fetch
            ]);
            $annonces = json_decode($responseAnnonces->getContent(), true);
            if(json_decode($responseAnnonces->getContent(), true) == []) $id -= 1;
            $tag = false;
            $nbPage = round($ar->countAllAnnonces() / $fetch);
        }else{
            $responseAnnonces = $this->forward('App\Controller\ApiController::annoncesByTag', [
                'token' => $_ENV['API_TOKEN'],
                'skip' => $skip,
                'fetch' => $fetch,
                'listNom' => $nom
            ]);
            $annonces = json_decode($responseAnnonces->getContent(), true);
            if(json_decode($responseAnnonces->getContent(), true) == []) $id -= 1;
            $tag = true;
            $nbPage = round($ar->countAllAnnoncesByTag($nom) / $fetch);
            /*
            $responseAnnonces = $this->forward('App\Controller\ApiController::singleTag', [
                'token' => $_ENV['API_TOKEN'],
                'nom' => $nom
            ]);
            if (json_decode($responseAnnonces->getContent(), true) != []) {
                $annonces = json_decode($responseAnnonces->getContent(), true)[0]['annonces'];
                krsort($annonces);
                $annonces = array_values($annonces);

                foreach ($annonces as $key => $value) if (!$value['actif']) unset($annonces[$key]);

                $annonces = array_values($annonces);
            } else {
                return $this->redirectToRoute('annonces');
            }*/
        }
        // If the user is connected
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $responseWishlist = $this->forward('App\Controller\ApiController::wishlist', [
                "id" => $this->getUser()->getId(),
                'token' => $_ENV['API_TOKEN']
            ]);
            $wishlist = json_decode($responseWishlist->getContent(), true);
        }

        $responseTags = $this->forward('App\Controller\ApiController::allTags', [
            'token' => $_ENV['API_TOKEN']
        ]);
        $tags = json_decode($responseTags->getContent(), true);

        return $this->render('annonce/annonces.html.twig', [
            'annonces' => $annonces,
            'wishlist' => $wishlist,
            'id' => $id,
            'nbPage' => $nbPage,
            'tag' => $tag,
            'nom' => $nom,
            'tags' => $tags
        ]);
    }

    
    /**
     * @Route("/annonce/editer/{id}", name="annonceModif")
     * @Route("/annonce/editer", name="annonceModifWithoutId")
     */
    public function annonceModif(? Annonce $annonce,int $id = 0,Request $req, EntityManagerInterface $em, TagRepository $tags): Response
    {
        $allTags = $tags->findAll();
        $user = $this->getUser();
        $date = new DateTime();
        if ($id != 0) {
            if (isset($annonce) && $annonce->getIdCompte()->getId() === $this->getUser()->getId()) {
                $form = $this->createForm(AnnonceFormType::class, $annonce);
            } else {
                return $this->redirectToRoute('user');
            }
        } else {
            $annonce = new Annonce;
            $form = $this->createForm(AnnonceFormType::class);
        }
        $form->handleRequest($req);
        
        if($form->isSubmitted() && $form->isValid()){
            
            
            if(isset($_POST['image'])){
                $radio_image = $_POST['image'];
            }
            
            // je récupere dynamiquement la valeur des tags qui sont postés
            for ($i=1; $i < 4 ; $i++) { 

                $targetTag = $_POST['tag'.$i];

                if(isset($targetTag)){
                   if( $targetTag != 'tag'){ // J'inclue pas quand la valeur = tag 
                     
                    $hehe = $tags->find($targetTag);
                    $annonce->addListeIdTag($hehe);
                    
                   }

                }
            }

            //PARTIE IMAGE 
            $imagePresentation=false;

            //Je viens chercher si il y a deja une image qui a une présentation pour éviter d'en mettre +1
             foreach( $annonce->getImages() as $annonceImage){
                
                if( $annonceImage->getId() == $radio_image){
                   $annonceImage->setPresentation(true);
                }else{
                    $annonceImage->setPresentation(false);
                }
                if ($annonceImage->getPresentation()){
                    $imagePresentation = true;
                }
             };
         


            // On récupère les images transmises
            $images = $form->get('images')->getData();
            //On vient chercher le 1er index pour le mettre en img de présentation
            $count = 1;
            $length = count($images);
            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

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
                if($count === 1 &  $imagePresentation == false){
                    $img->setPresentation(True);
                }
                $count ++;
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

            if (!$id) {

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
            'annonce'=>$annonce,
            'tags'=>$allTags,
            
        ]);
    }

    /**
     * @Route("/annonce/{id}/{state}", name="annonceState")
     */
    public function annonceState(AnnonceRepository $rep, $state, $id, Request $req, EntityManagerInterface $em): Response
    {
        $annonce = $rep->find($id);
        if($annonce && $annonce->getIdCompte()->getId() === $this->getUser()->getId()){
            
            if($state == "archiver"){
                $annonce->setActif(0);
            }else{
                $annonce->setActif(1);
            }
            $em->flush();
            
            return $this->redirectToRoute('user');     
        }              
    }
    /**
     * @Route("/supprime/image/{id}", name="annonces_delete_image")
     */

    public function deleteImage(Image $image, Request $request)
    {
        $isAjax = $request->isXMLHttpRequest();
        if (!$isAjax) return new Response('', 404);

        if($image->getIdAnnonce()->getIdCompte()->getId() === $this->getUser()->getId()){
          if ($image->getPresentation() == 1){
            return  $this->json(['error' => 'Vous ne pouvez pas supprimer votre image principale. Modifiez la avant.'], 400);
          }else{


            // On récupère le nom de l'image
            $nom = $image->getNom();
            // On supprime le fichier
            unlink($this->getParameter('images_directory') . '/' . $nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return $this->json(['success' => 1], 200);
            // return new JsonResponse(['success' => 1]);

                // On répond en json
                return $this->json(['success' => 1],200);
            }
        }else{
            return new Response('', 404);
        }
    }

    /**
     * @Route("/annonce/{id}", name="annonce-single")
     */
    public function annonce($id = 0): Response
    {
        $wishlist = '';
        $responseAnnoncesPaid = $this->forward('App\Controller\ApiController::allAnnoncesPaid', [
            'token' => $_ENV['API_TOKEN']
        ]);
        $responseAnnonce = $this->forward('App\Controller\ApiController::singleAnnonce', [
            'token' => $_ENV['API_TOKEN'],
            'id' => $id
        ]);
        $annonce = json_decode($responseAnnonce->getContent(), true);
        if((isset($annonce["code"]) && $annonce["code"] != 200) || $annonce == null) return $this->redirectToRoute('annonces');

        // If the user is connected
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            $responseWishlist = $this->forward('App\Controller\ApiController::wishlist', [
                "id" => $this->getUser()->getId(),
                'token' => $_ENV['API_TOKEN']
            ]);
            $wishlist = json_decode($responseWishlist->getContent(), true);
        }

        return $this->render('annonce/annonce.html.twig', [
            'annonce' => $annonce,
            'wishlist' => $wishlist,
            'annonces' => json_decode($responseAnnoncesPaid->getContent(), true)
        ]);
    }

    /**
     * @Route("/boost/annonce/{id}/", name="boost")
     */
    public function annonceBoost(Annonce $annonce, Request $req, EntityManagerInterface $em): Response
    {    
        if(isset($_POST)){
            //C'est moche mais ça m'a saoulé
            $annonce->setAnnoncePayante(1); 
            $em->flush();

            return $this->redirectToRoute('user');     
        }              
    }

    /**
    * @Route("/supprime/tag/{idTag}&{annonce}", name="supprTag")
    */
    public function deleteTag(TagRepository $tag, AnnonceRepository $annonces, Request $request, $idTag, $annonce, EntityManagerInterface $em): Response
    {
      
        $annonce = $annonces->find($annonce);
        $idTag = $tag->find($idTag);
        $annonce->removeListeIdTag($idTag);
        $em->flush();
     
        return $this->redirectToRoute('user');
    
       
    }
    

    
}
