<?php
// Inclure le chargeur automatique généré par Composer pour charger les dépendances
require __DIR__ . '/../vendor/autoload.php';

// Ici, il est mentionné qu'il y a eu des problèmes car toutes les dépendances n'étaient pas correctement créées par Composer 
// au moment de la construction, ce qui a entraîné un crash du serveur lors du démarrage du script

// Utiliser les espaces de noms nécessaires pour Ratchet et la classe Chat personnalisée
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Classes\Chat; // Supposons que vous avez une classe Chat qui implémente Ratchet\MessageComponentInterface

// Créer le serveur WebSocket en utilisant une fabrique IoServer
$server = IoServer::factory(
    // HttpServer enveloppe WsServer qui, à son tour, enveloppe notre application Chat
    new HttpServer(
        new WsServer(
            new Chat() // Instancier votre application Chat
        )
    ),
    8080 // Le port sur lequel le serveur WebSocket va écouter
);

// Exécuter le serveur
$server->run();
