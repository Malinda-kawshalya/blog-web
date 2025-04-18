<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
require_once __DIR__ . '/vendor/autoload.php';

class ChatServer implements MessageComponentInterface {
    protected $clients;
    protected $userConnections = []; // Maps connection resource IDs to user IDs

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "Chat server started!\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg);
        
        // Handle authentication messages
        if (isset($data->type) && $data->type === 'auth') {
            $this->userConnections[$from->resourceId] = [
                'user_id' => $data->user_id,
                'user_type' => $data->user_type, // 'student' or 'teacher'
                'conversation_id' => $data->conversation_id
            ];
            echo "User {$data->user_id} ({$data->user_type}) connected to conversation {$data->conversation_id}\n";
            return;
        }
        
        // Handle chat messages
        if (isset($data->type) && $data->type === 'chat') {
            $sender = $this->userConnections[$from->resourceId];
            
            // Store the message in the database (this would be your DB logic)
            // saveMessageToDatabase($sender['user_id'], $sender['user_type'], $data->conversation_id, $data->message);
            
            // Forward the message to all users in the same conversation
            foreach ($this->clients as $client) {
                if (isset($this->userConnections[$client->resourceId]) && 
                    $this->userConnections[$client->resourceId]['conversation_id'] === $sender['conversation_id']) {
                    $client->send(json_encode([
                        'type' => 'message',
                        'user_id' => $sender['user_id'],
                        'user_type' => $sender['user_type'],
                        'message' => $data->message,
                        'time' => date('Y-m-d H:i:s')
                    ]));
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        unset($this->userConnections[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Run the server application
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8080
);

echo "Starting chat server on port 8080...\n";
$server->run();