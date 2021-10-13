<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
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
           for($i = 1 ; $i <=30 ; $i++){
                $value = 'user'.$i;
                $date = new DateTime();
                 
                $user = new User();
                $password = $this->encoder->encodePassword($user, $value);
                $user->setEmail($value."@gmail.com")
                ->setPassword($password)
                ->setActif(1)
                ->setPrenom($value)
                ->setPseudo($value)
                ->setNom($value)
                ->setDateCreation($date)
                ->setDateModification($date);
                
                
                $manager->persist($user);
                
                } 
                $manager->flush();

                
            }
           


}
