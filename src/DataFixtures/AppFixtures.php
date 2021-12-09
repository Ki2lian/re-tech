<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Image;
use App\Entity\Tag;
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
        

               
                

                
            } 
            $tags = ["Smartphone", "iPhone", "Samsung", "Huawei", "Xiaomi", "Sony", "Honor", "OnePlus", "Oppo", "Google Pixel", "Nokia", "Crosscall", "LG", "BlackBerry", "Motorola", "HTC", "Asus", "Wiko", "Doro", "Téléphone fixe", "MacBook", "Ordinateur portable", "Ordinateur de bureau", "Chromebook", "Ultrabook", "Imprimante", "Périphérique et accessoire", "Tablette", "iPad", "Samsung Galaxy Tab", "Microsoft Surface", "Lenovo", "Archos", "Storex", "Danew", "Acer", "AirPods", "Écran d'ordinateur", "Souris", "Casque micro", "Clavier", "Webcam", "Console", "PlayStation", "Xbox", "Nintendo", "TV", "TV 4K", "TV 3D", "Vidéoprojecteur", "Montre connectée", "Maison connectée", "Hoverboard", "Vélos électriques", "Trotinettes électriques", "Processeur", "Carte graphique", "Disque dur", "Carte mère", "SSD", "Refroidissement PC", "Connectique", "Mémoire", "Alimentation", "Ventilateur", "Carte son", "Raspberry", "Micro", "Tapis de souris", "Stockage externe", "NAS", "Clé WiFi", "Répéteur WiFi", "Carte WiFi", "Switch", "Routeur WiFi", "Câble réseau", "Carte réseau", "Aerocool", "Aorus", "Alienware", "Dell", "Corsair", "Haier", "HP", "ibm", "Msi", "JBL", "BOSE", "Philips", "EPSON", "Hypson", "Nikon", "NEC", "Panasonic", "Hitachi", "Apple", "Canon", "Kodak" ];
                foreach ($tags as $key => $value){ 
                    $tag = new Tag(); 
                    $tag->setNom($value); 
                    $manager->persist($tag); 
                } 
                 
                $manager->flush();
        }

}