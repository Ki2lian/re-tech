<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\AnnonceRepository;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManager;
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
    public function index(ConversationRepository $cr): Response
    {
        $conversations = $cr->findAllByIdUser($this->getUser()->getId());
        $destinataire = [];
        $cpt = 0;
        foreach ($conversations as $key => $conversation) {
            if($conversation->getCompte()->getId() === $this->getUser()->getId()){
                $destinataire[$key]["pseudo"] = $conversation->getCompte2()->getPseudo();
            }else{
                $destinataire[$key]["pseudo"] = $conversation->getCompte()->getPseudo();
            }
            $destinataire[$key]["id"] = $conversation->getId();
            
            $responseConversation = $this->forward('App\Controller\ApiController::getConversation', [
                'token' => $_ENV['API_TOKEN'],
                'id' => $conversation->getId()
            ]);
            $conv = json_decode($responseConversation->getContent(), true);
            $messages = $conv[0]["messages"];
            $lastMessage = end($messages);
            $destinataire[$key]["message"] = $lastMessage;
            if(!$lastMessage) unset($destinataire[$key]);
            else if(!$lastMessage["isReceipt"] && $lastMessage['compte']['id'] != $this->getUser()->getId()) $cpt++;
        }

        return $this->render('websocket/index.html.twig', [
            'destinataire' => $destinataire,
            'cpt' => $cpt
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
     * @Route("/save", name="save_message", methods={"POST"})
     */
    public function save(Request $request, EntityManagerInterface $entityManager, ConversationRepository $cr): Response
    {
        date_default_timezone_set('Europe/Paris');
        $isAjax = $request->isXMLHttpRequest();
        if (!$isAjax) return new Response('', 404);
        $dataMessage = $request->get('dataMessage');
        $data = json_decode($dataMessage, true);
        $conv = $data['conversation'];
        $tokenVerif = $data['token'];
        $sender = $data['sender'];
        $receiver = $data['receiver'];

        $responseConversation = $this->forward('App\Controller\ApiController::getConversation', [
            'token' => $_ENV['API_TOKEN'],
            'id' => $conv['id']
        ]);
        $conversation = json_decode($responseConversation->getContent(), true);
        if(isset($conversation["code"]) && $conversation["code"] != 200 || empty($conversation)) return $this->redirectToRoute('message_list');
        $conversation = $conversation[0];
        if($conversation['compte']['id'] !== $this->getUser()->getId() && $conversation['compte2']['id'] !== $this->getUser()->getId()) return $this->redirectToRoute('message_list');
        if(hash("sha512", 'messagerie-'.$sender['id'].'-'.$receiver['id'].'-'.$conv['id']) !== $tokenVerif) return $this->redirectToRoute('message_list');
        
        $date = new \DateTime();
        $date->setTimestamp($data['message']['date']);
        $message = new Message();
        $message->setCompte($this->getUser());
        $message->setContenu($data['message']['contenu']);
        $message->setDateCreation($date);
        $message->setConversation($cr->findOneBy(array('id' => $conversation['id'])));
        $entityManager->persist($message);
        $entityManager->flush();
        return $this->json(["code" => 200, "message" => "Message sauvegardé"], 200);
    }

    /**
     * @Route("/{id}", name="message")
     */
    public function message($id = 0, EntityManagerInterface $em, MessageRepository $mr): Response
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

        // $conversation = $cr->findOneBy(array('id' => $conversation['id']));
        // $conversation
        $messages = $mr->findBy(array('compte' => $receiver['id'], 'conversation' => $conversation['id'], 'isReceipt' => 0));
        foreach($messages as $key => $message){
            $message->setIsReceipt(1);
            $em->persist($message);
        }
        $em->flush();

        return $this->render('websocket/message.html.twig', [
            "conversation" => $conversation,
            "sender" => $sender,
            "receiver" => $receiver,
            "annonce" => $conversation['annonce'],
            'wishlist' => $wishlist,
            "hash" => hash('sha512', 'messagerie-'.$sender['id'].'-'.$receiver['id'].'-'.$conversation['id'])
        ]);
    }
}
