<?php
namespace App\Websocket;

use App\Entity\Message;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageHandler extends AbstractController implements MessageComponentInterface
{
    protected $connections;
    
    public function __construct()
    {
        $this->connections = new SplObjectStorage;
    }
 
    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections->attach($conn);
    }
 
    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach($this->connections as $connection)
        {
            // if($connection === $from)
            // {
            //     continue;
            // }

            $data = json_decode($msg, true);

            $tokenVerif = $data['token'];
            $sender = $data['sender'];
            $receiver = $data['receiver'];
            $conv = $data['conversation'];
            $message = $data['message'];

            var_dump($this->user);
            /*
            if(hash("sha512", 'messagerie-'.$sender['id'].'-'.$receiver['id']) === $tokenVerif){
                $responseConversation = $this->forward('App\Controller\ApiController::getConversation', [
                    'token' => $_ENV['API_TOKEN'],
                    'id' => $conv['id']
                ]);
                $conversation = json_decode($responseConversation->getContent(), true);
                if(isset($conversation["code"]) && $conversation["code"] != 200 || empty($conversation)) return;
                $conversation = $conversation[0];
                //if($sender['id'] !== $this->getUser()->getId()) return;

                
                $message = new Message();
                $message->setCompte($this->getUser());
                $message->setContenu($message['contenu']);
                $message->setConversation($conversation);
                echo new \DateTime($message['date']);

            }*/

            $connection->send($msg);
        }
    }
 
    public function onClose(ConnectionInterface $conn)
    {
        $this->connections->detach($conn);
    }
 
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $this->connections->detach($conn);
        $conn->close();
    }
}