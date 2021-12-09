<?php

namespace App\Controller;

use App\Entity\Log;
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
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher,AuthenticationUtils $authenticationUtils ): Response
    {  
        $date = new DateTime();
        $user = new User();
        $log  = new Log();
        $form = $this->createForm(RegistrationFormType::class);             
        $form->handleRequest($request);
        $error = $authenticationUtils->getLastAuthenticationError();
        
                if ($form->isSubmitted() && $form->isValid()) {
                    if(!$request->get('cgv')) return $this->redirectToRoute('app_register');
                  
                    $hashedPassword = $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    );
                    $user->setPassword($hashedPassword);

                    if ($form['email'] != $user->getEmail()){
                        $user->setEmail(
                            $form->get('email')->getData()
                        );
                    }
                    $user->setPseudo(
                        $form->get('pseudo')->getData()
                    );
                    $user->setActif(1);
                    $user->setDateCreation($date);
                    $user->setDateModification($date);
        
                    
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $lastId = $user->getId();

                    $log->setType("user");
                    $log->setDateLog($date);
                    $log->setAction("Nouvel utilisateur créé | ID : $lastId | Pseudo : " . $form->get('pseudo')->getData());
                    
                    $entityManager->persist($log);
                    $entityManager->flush();
        
                    return $this->redirectToRoute('accueil');
                }
       
        return $this->render('security/login.html.twig',  [
            'registrationForm' => $form->createView(),
            'error' => $error
        ]);
    }
}
