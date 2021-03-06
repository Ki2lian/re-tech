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
use Faker\Factory;
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
            $faker = Factory::create('fr_FR');

            $date = new DateTime();
            for($i = 1 ; $i <=30 ; $i++){

                $value = 'user'.$i;

                $user = new User();
                $password = $this->encoder->encodePassword($user, $value);
                $user->setEmail($faker->email())
                ->setPassword($password)
                ->setActif(1)
                ->setPrenom($faker->firstName())
                ->setPseudo($faker->lastName())
                ->setNom($faker->lastName())
                ->setDateCreation($date)
                ->setDateModification($date)
                ->setActif(1)
                ->setDescription($faker->realText(200));
               
                
                $manager->persist($user);

                $annonce = new Annonce();
                $annonce->setTitre ($faker->realText(30))
                ->setAnnoncePayante(0)
                ->setDescription($faker->realText(200))
                ->setPrix(rand(50,500))
                ->setDateCreation($date)
                ->setActif(1)
                ->setDateModification($date)
                ->setIdCompte($user);

                
                $manager->persist($annonce);

                    $image = new Image();
                    $image->setNom('image')
                        ->setPresentation(0)
                        ->setJeton('token')
                        ->setIdAnnonce($annonce);

                

                $manager->persist($image);
        

               
                

                
            } 
            $tags = ["Smartphone", "iPhone", "Samsung", "Huawei", "Xiaomi", "Sony", "Honor", "OnePlus", "Oppo", "Google Pixel", "Nokia", "Crosscall", "LG", "BlackBerry", "Motorola", "HTC", "Asus", "Wiko", "Doro", "T??l??phone fixe", "MacBook", "Ordinateur portable", "Ordinateur de bureau", "Chromebook", "Ultrabook", "Imprimante", "P??riph??rique et accessoire", "Tablette", "iPad", "Samsung Galaxy Tab", "Microsoft Surface", "Lenovo", "Archos", "Storex", "Danew", "Acer", "AirPods", "??cran d'ordinateur", "Souris", "Casque micro", "Clavier", "Webcam", "Console", "PlayStation", "Xbox", "Nintendo", "TV", "TV 4K", "TV 3D", "Vid??oprojecteur", "Montre connect??e", "Maison connect??e", "Hoverboard", "V??los ??lectriques", "Trotinettes ??lectriques", "Processeur", "Carte graphique", "Disque dur", "Carte m??re", "SSD", "Refroidissement PC", "Connectique", "M??moire", "Alimentation", "Ventilateur", "Carte son", "Raspberry", "Micro", "Tapis de souris", "Stockage externe", "NAS", "Cl?? WiFi", "R??p??teur WiFi", "Carte WiFi", "Switch", "Routeur WiFi", "C??ble r??seau", "Carte r??seau", "Aerocool", "Aorus", "Alienware", "Dell", "Corsair", "Haier", "HP", "ibm", "Msi", "JBL", "BOSE", "Philips", "EPSON", "Hypson", "Nikon", "NEC", "Panasonic", "Hitachi", "Apple", "Canon", "Kodak", "Audio", "Ordinateur"];
                foreach ($tags as $key => $value){ 
                    $tag = new Tag(); 
                    $tag->setNom($value); 
                    $manager->persist($tag); 
                } 
                 
                $manager->flush();
        }

}