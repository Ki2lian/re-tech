<?php

namespace App\Controller;
use DateTime;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="app_register")
     */
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {  
                if ($this->getUser()) {
                    return $this->redirectToRoute('target_path');
                    }
                    // get the login error if there is one
                    $error = $authenticationUtils->getLastAuthenticationError();
                    // last username entered by the user
                    $lastUsername = $authenticationUtils->getLastUsername();
            
       

        return $this->render('security/login.html.twig',  ['last_username' => $lastUsername, 'error' => $error,
           
        ]);
    }


    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {  
        $date = new DateTime();
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class);             
        $form->handleRequest($request);

    
                if ($form->isSubmitted() && $form->isValid()) {

                    $hashedPassword = $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    );
                    $user->setPassword($hashedPassword);
                    $user->setEmail(
                        $form->get('email')->getData()
                    );
                    $user->setPseudo(
                        $form->get('pseudo')->getData()
                    );
                    $user->setActif(1);
                    $user->setDateCreation($date);
                    $user->setDateModification($date);
        
                    
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
        
                    return $this->redirectToRoute('accueil');
                }
       
        return $this->render('security/login.html.twig',  [
            'registrationForm' => $form->createView(),
        ]);
    }
}
