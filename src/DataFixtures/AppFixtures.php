<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Annonce;
use App\Entity\Image;
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


                $image = new Image();
                $image->setContenu(0x89504e470d0a1a0a0000000d494844520000015e00000096040300000064c9fd440000001b504c5445cccccc9696969c9c9caaaaaac5c5c5b1b1b1a3a3a3b7b7b7bebebe877b72a8000000097048597300000ec400000ec401952b0e1b0000047049444154789ced9a4d6fdb461086875f228f5cca9273246db7f65172132047b232121f45a1757b140b03f251725b2547290efabb3bb3bb34570cad366849b6c03c807644728c7d3d3b3bbb2b10806118866118866118866118866118866118866118866118866118866118866198af639644afd1788288f19b7b1dbd7dc13710d2e4e41a1d776d0ba9730ee03cebdda35dbfe42ccdb4d47bc4b52d36d4f7086050ea0dc89e363bdb4a6fa1f51e736d8b24fafc3e1129d8e30fc88e343ddc1651dae89c2bbdc90a5d3f1e776d095f2ca9df3958659c3611057bdee0ebfe2194de32075e766d0d47ec685c9790bdd2770afce2ca44ae9308a5d7a5bf39eeda1a3223a9cffd89be23425272d2e02bb4de00d3e72f5c5bc3926509e3bb09d58d404cb0cd55766434f21ea50cf1cb5922f5faa2c1b523fc8fbae33c56373c999cfba1be8849f4aef456f5c18b1a5cbb64801d4f9777d10a74424336960fdc042bddb412a4f43a63aff8765d73ed90db02bb2cce303b2f483b89b074083722754595a04aef20c2998765ecd0b53370b5ba57935f89480d11b69898054be9b5e5d2725173ed52ef6b9afc3fdc4e318f9508fb794a9dec0d3deab62546374f625873ed522fc69546ddc7a60ca17e580c0b63fe977a53992935d7cec0b8c6f0e38ed48dea41cb84b91ee8faf033c875ada7f8d2a8eb18eec7f5a4c46ddbae7234b4e19ad853fe22b92e59d8776dd2bbc22c57662c45dc537d0019d7524fada8fac2dc7f997a93b08ffabbb8a4b62a5ff545cb16e6feeb506f1febdb66446d36f67f9236927b3563539047c672a1f5fe4e32718f5473ed04159dfd50cd1dba3ad874b9e25561c44fe9950f69cfd1c7fe4cea2c4ed5d852ad3dd8d43a62626c77b45e1951da73f4b0ff75283d3d7112d0b07bd41c1c1a32913ae576f259ef9efec71c9b1ece1781187e7e5f609f49f45b30a5bee5a1acdc916332b8c689b25cd12ed3273aa31eb876843c9b8fd5d15cd65af3d02ba39e5705d6d6e70b62d9cff9589ee3275468955527fc75a96f4239f13ce2ba9e91c7b0e6da19db44fd4633d3d6fcd1664383ed55154deb75afc5f9aee6ca300cc3300cc3300cc3300cc3300cc3300cc330ccbf82a53fff17fe9b7a857ccdbd899ade10af2c7a953502b04350ef91ce9215c03b710f560c90b4ab54cb70bf7be149835ed98e77b0d57add6fd2276c6e16a9f5007ed1be5a12f03d5c9f839dfaf8c53b9b80b508c9386739e95d9cc3608d4f0cbda74b5869bdce5c35dec4dac260d3915eeffedddc5967f02bdcddbc01eb5e9abb1b0a97858f8218de987ac3ab20d67aed54356e6cd9bb4f9d243be6839dba713059acdfc20546d24aa5b9808cf4e22358e1a77c0d9bf46e076badd72a9bd0f297ab4ef4e27c93618baf963169a2eec98465fe86f0c9a19721aaf8da8ff0657ce132ee26bed4210571f9b0c4a04a51648cf8da0b38d0eb8de0cbfc857cde955eca5f58ac1fe7b04d67248a4c95bfe09f1eea9517b235ea037454aca95fac0fb04fb314fce48a7a2553d507f0e306bd2a6766a2acbf5de9fd1b0cba7e97e71ff2d8b780afc3baea5b01c3304c3ffc097f21aaf2980193a20000000049454e44ae426082)
                ->setPresentation('Loremloremlorem')
                ->setJeton('Jeton'.$i); 
                
                $manager->persist($image);


                $annonce = new Annonce();
                $annonce->setTitre ("Titre de l'annonce n°$i")
                ->setAnnoncePayante(0)
                ->addImage($image)
                ->setDescription("Description de l'annonce n°$i")
                ->setPrix(rand(50,500))
                ->setDateCreation($date)
                ->setActif(1)
                ->setDateModification($date)
                ->setIdCompte($user);
                
                $manager->persist($annonce);
        

                $image->setIdAnnonce($annonce);
                $manager->persist($image);

                
            } 
                $manager->flush();
        }

}