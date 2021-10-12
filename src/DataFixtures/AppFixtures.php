<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
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
               $TabUser[] = 'user'.$i;
           }        
    
            foreach ($TabUser as $value) {
    
                $user = new User();
                $password = $this->encoder->encodePassword($user, $value);
                $user->setEmail($value."@gmail.com")
                ->setPassword($password)
                ;
                $manager->persist($user);
                
                } 
                $manager->flush();
            }
           


}
