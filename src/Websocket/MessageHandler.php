<?php
namespace App\Websocket;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
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
    private $users;
    
    public function __construct()
    {
        $this->connections = new SplObjectStorage;
        $this->users = [];
    }
 
    public function onOpen(ConnectionInterface $conn)
    {
        $this->connections->attach($conn);
        $this->users[$conn->resourceId] = (array) $conn;
        echo $conn->resourceId.' is connected'. PHP_EOL;
    }
 
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if($data['command']['type'] === "connection"){
            $sender = $data['sender'];

            if(isset($this->users[$from->resourceId]) && !isset($this->users[$from->resourceId]['id'])){
                $this->users[$from->resourceId]['id'] = $sender['id'];
                echo $from->resourceId." (id websocket) = ". $this->users[$from->resourceId]['id']." (id user)" .PHP_EOL;
            }
        }
        foreach($this->connections as $connection)
        {
            if($data['command']['type'] === "message"){
                if($connection === $from)
                {
                    continue;
                }
                $tokenVerif = $data['token'];
                $sender = $data['sender'];
                $receiver = $data['receiver'];
                $conv = $data['conversation'];
                $message = $data['message'];
                if(hash("sha512", 'messagerie-'.$sender['id'].'-'.$receiver['id'].'-'.$conv['id']) === $tokenVerif){
                    if($this->users[$connection->resourceId]['id'] === $receiver['id']){
                        $connection->send($msg);
                        echo "The user (".$this->users[$from->resourceId]['id'].") has sent the message '".$message['contenu']."' to the user (".$this->users[$connection->resourceId]['id'].")" . PHP_EOL;
                    }
                }
            }
        }
    }
 
    public function onClose(ConnectionInterface $conn)
    {
        unset($this->users[$conn->resourceId]);
        $this->connections->detach($conn);
    }
 
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        unset($this->users[$conn->resourceId]);
        $this->connections->detach($conn);
        $conn->close();
    }
}