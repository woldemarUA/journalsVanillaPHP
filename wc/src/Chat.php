<?php

namespace Classes;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    // Stockage des clients connectés et de l'instance de base de données
    protected $clients;
    protected $db;

    // Constructeur : initialise le stockage des clients et la connexion à la base de données
    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
        $this->db = new Database();
    }

    // Appelé lorsqu'une nouvelle connexion WebSocket est ouverte
    public function onOpen(ConnectionInterface $conn)
    {
        // Attacher le nouveau client à l'ensemble des clients
        $this->clients->attach($conn);
        echo "Nouvelle connexion ! {$conn->resourceId} \n";
    }

    // Gère les messages reçus des clients
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true); // Décode le message reçu au format JSON

        // Traite les messages en fonction de leur type
        if (isset($data['type'])) {
            switch ($data['type']) {
                case 'createChat':
                    // Créer un nouveau chat
                    $this->createChat($data['chatTitle'], $data['participants'], $from);
                    break;
                case 'sendMessage':
                    // Envoyer un message dans un chat
                    $this->sendMessage($data['chatId'], $data['message'], $data['recipientId'], $data['senderId'], $from);
                    break;
                    // Ajouter d'autres cas au besoin
            }
        }
    }

    // Crée un nouveau chat dans la base de données
    protected function createChat($chatTitle, $participants, ConnectionInterface $from)
    {
        try {
            // Insérer le titre du chat dans la base de données
            $sql = "INSERT INTO Chats (chatTitle) VALUES (?)";
            $this->db->query($sql, [$chatTitle]);
            $chatId = $this->db->lastInsertId(); // Récupérer l'ID du dernier enregistrement inséré

            // Ajouter les participants au chat
            foreach ($participants as $participantId) {
                $this->db->query("INSERT INTO ChatParticipants (chatId, userId) VALUES (?, ?)", [$chatId, $participantId]);
            }

            // Notifier le créateur du chat de sa création
            $from->send(json_encode(['type' => 'chatCreated', 'chatId' => $chatId]));
        } catch (\PDOException $e) {
            // Gérer les erreurs de base de données
            error_log("Erreur de base de données dans createChat: " . $e->getMessage());
            $from->send(json_encode(['type' => 'error', 'message' => 'Échec de la création du chat']));
        }
    }

    // Envoie un message dans un chat et le diffuse aux participants
    protected function sendMessage($chatId, $message, $recipientId, $senderId, ConnectionInterface $from)
    {
        try {
            // Insérer le message dans la base de données
            $sql = "INSERT INTO `Messages` (`chatId`, `senderId`, `recipientId`, `message`) VALUES (:chatId, :senderId, :recipientId, :message)";
            $this->db->query($sql, ["chatId" => $chatId, "senderId" => $senderId, "recipientId" => $recipientId, "message" => $message]);

            // Diffuser le message aux participants du chat
            foreach ($this->clients as $client) {
                $client->send(json_encode(['type' => 'chatMessage', "senderId" => $senderId, "recipientId" => $recipientId, "message" => $message, "chatId" => $chatId]));
            }
        } catch (\PDOException $e) {
            // Gérer les erreurs de base de données
            error_log("Erreur de base de données dans sendMessage: " . $e->getMessage());
            $from->send(json_encode(['type' => 'error', 'message' => 'Échec de l\'envoi du message']));
        }
    }

    // Appelé lorsqu'une connexion WebSocket est fermée
    public function onClose(ConnectionInterface $conn)
    {
        // Détacher le client déconnecté de l'ensemble des clients
        $this->clients->detach($conn);
        echo "Connexion {$conn->resourceId} s'est déconnectée\n";
    }

    // Gère les erreurs de connexion
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Afficher l'erreur et fermer la connexion
        echo "Une erreur s'est produite : {$e->getMessage()}\n";
        $conn->close();
    }
}
