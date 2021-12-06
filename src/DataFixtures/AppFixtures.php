<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Image;
use App\Entity\Wishlist;
use DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    
        
        private $encoder;
    
        public function __construct(UserPasswordEncoderInterface $encoder)
        {
            $this->encoder = $encoder;
        }
       public function load(ObjectManager $manager)
       {
            $date = new DateTime();
            for($i = 1 ; $i <=30 ; $i++){

                $value = 'user'.$i;

                $user = new User();
                $password = $this->encoder->encodePassword($user, $value);
                $user->setEmail($value."@gmail.com")
                ->setPassword($password)
                ->setActif(1)
                ->setPrenom($value)
                ->setPseudo($value)
                ->setNom($value)
                ->setDateCreation($date)
                ->setDateModification($date)
                ->setActif(1)
                ->setDescription('Bonjour');
                
                $manager->persist($user);

                $annonce = new Annonce();
                $annonce->setTitre ("Titre de l'annonce n°$i")
                ->setAnnoncePayante(0)
                ->setDescription("Description de l'annonce n°$i")
                ->setPrix(rand(50,500))
                ->setDateCreation($date)
                ->setActif(1)
                ->setDateModification($date)
                ->setIdCompte($user);
                
                $manager->persist($annonce);
        
                $wish_list = new Wishlist();
                $wish_list->setDateCreation($date)
                ->setDateModifAnnonce($date)
                ->setIdAnnonce($annonce)
                ->setIdCompte($user);

                $manager->persist($wish_list);

                
            } 
                $manager->flush();
        }

}