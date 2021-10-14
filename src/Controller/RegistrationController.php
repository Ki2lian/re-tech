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

class RegistrationController extends AbstractController
{
    /**
     * @Route("/login", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response

    {   $date = new DateTime();
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {

            dd($form);
 
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
             dd($user);
             $entityManager->flush();
 
             return $this->redirectToRoute('accueil');
        }

        return $this->render('security/login.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
