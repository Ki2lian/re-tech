<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/message")
 */
class WebsocketController extends AbstractController
{
    /**
     * @Route("/", name="message_list")
     */
    public function index(): Response
    {
        return $this->render('websocket/index.html.twig', [
        ]);
    }

    /**
     * @Route("/check_conversation", name="message_check_conversation", methods={"POST"})
     */
    public function checkConversation(Request $request, AnnonceRepository $ar, EntityManagerInterface $entityManager): Response
    {
        $idAnnonce = $request->get('idAnnonce');
        if(intval($idAnnonce) == 0) return new Response('', 404);
        $annonce = $ar->findOneBy(array('id' => $idAnnonce));
        if($annonce === null || $this->getUser() === null ) return new Response('', 404);
        foreach ($annonce->getConversations() as $key => $value) {
            // Get id of conversation of the 2 user
            if( $value->getCompte()->getId() === $this->getUser()->getId() && $value->getCompte2()->getId() === $annonce->getIdCompte()->getId() ||
                $value->getCompte()->getId() === $annonce->getIdCompte()->getId() && $value->getCompte2()->getId() === $this->getUser()->getId()){
                return $this->redirectToRoute('message', array('id' => $value->getId()));
                break;
            }
        }
        
        // Create conversation between 2 users
        $conversation = new Conversation();
        $conversation->setCompte($this->getUser());
        $conversation->setCompte2($annonce->getIdCompte());
        $conversation->setAnnonce($annonce);
        $conversation->setDateCreation(new \DateTime());
        $entityManager->persist($conversation);
        $entityManager->flush();
        return $this->redirectToRoute('message', array('id' => $conversation->getId()));
    }

    /**
     * @Route("/{id}", name="message")
     */
    public function message($id = 0): Response
    {
        $responseConversation = $this->forward('App\Controller\ApiController::getConversation', [
            'token' => $_ENV['API_TOKEN'],
            'id' => $id
        ]);

        $conversation = json_decode($responseConversation->getContent(), true);
        if(isset($conversation["code"]) && $conversation["code"] != 200 || empty($conversation)) return $this->redirectToRoute('message_list');
        $conversation = $conversation[0];
        if($conversation['compte']['id'] !== $this->getUser()->getId() && $conversation['compte2']['id'] !== $this->getUser()->getId()) return $this->redirectToRoute('message_list');
        if($conversation['compte']['id'] === $this->getUser()->getId()){
            $sender = $conversation['compte'];
            $receiver = $conversation['compte2'];
        }else{
            $sender = $conversation['compte2'];
            $receiver = $conversation['compte'];
        }

        // If the user is connected
        $securityContext = $this->container->get('security.authorization_checker');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $responseWishlist = $this->forward('App\Controller\ApiController::wishlist', [
                "id" => $this->getUser()->getId(),
                'token' => $_ENV['API_TOKEN']
            ]);
            $wishlist = json_decode($responseWishlist->getContent(), true);
        }

        return $this->render('websocket/message.html.twig', [
            "conversation" => $conversation,
            "sender" => $sender,
            "receiver" => $receiver,
            "annonce" => $conversation['annonce'],
            'wishlist' => $wishlist,
            "hash" => hash('sha512', 'messagerie-'.$sender['id'].'-'.$receiver['id'])
        ]);
    }
}
