<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\UserFormType;
use App\Repository\AnnonceRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(AnnonceRepository $annonce): Response

    {   
        $user = $this->getUser();
        //Récupère les annonces du user connecté 
        $annonces = $user->getAnnonces();

        //Récupère les annonces réservées par le user
        $reservations = $user->getTransactions();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'annonces'=> $annonces,
            'transactions' => $reservations
        ]);
    }


    /**
     * @Route("/modifCompte", name="userModif")
     */
    public function modifUser(Request $req, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {   
        $date = new DateTime();
        $user= $this->getUser();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            
            $user->setEmail(
                $form->get('email')->getData()
            );
  
            $user->setPassword($hashedPassword);

            $user->setNom(
                $form->get('nom')->getData()
            );
            $user->setPrenom(
                $form->get('prenom')->getData()
            );
          
            $user->setDescription(
                $form->get('description')->getData()
            );
           
            $user->setActif(1);
            $user->setDateModification($date);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user');                   
        }
        
    return $this->render('user/modificationCompte.html.twig', [
        'formUsermodif' => $form->createView()
    ]);
    }

    /**
     * @Route("/supprCompte", name="supprCompte")
     */
    public function supprCompte(EntityManagerInterface $em)
    {
      
        $user = $this->getUser();
        
        $this->container->get('security.token_storage')->setToken(null);
        dump($em->remove($user));
        $user->setActif(0);
        $em->flush();
        
        // Ceci ne fonctionne pas avec la création d'une nouvelle session !
        $this->addFlash('success', 'Votre compte utilisateur a bien été supprimé !'); 
        
        return $this->redirectToRoute('accueil');
    }
}