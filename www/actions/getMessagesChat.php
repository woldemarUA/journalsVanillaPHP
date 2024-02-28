<?php
// Charge les dépendances et les classes automatiquement
require_once __DIR__ . '/../init/autoload.php';

// Récupère l'identifiant du chat depuis la requête GET, sinon null si non défini
$chatId = isset($_GET['chatId']) ? $_GET['chatId'] : null;

// Crée une nouvelle instance de la classe User, en passant une nouvelle instance de Database
$user = new \Auth\Classes\User(new \Db\Classes\Database);

// Appelle la méthode getMessages de l'utilisateur pour récupérer les messages du chat spécifié
$result = $user->getMessages($chatId);

// Définit le type de contenu de la réponse HTTP comme étant du JSON
header('Content-Type: application/json');

// Encode le résultat en JSON et l'envoie comme réponse HTTP
echo json_encode($result);
