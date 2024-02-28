<?php
// Inclut le fichier autoload.php pour charger automatiquement les classes nécessaires
require_once __DIR__ . '/../init/autoload.php';

// Récupère l'ID de l'utilisateur à partir de la session
$userId = $_SESSION['user_id'];

// Crée une instance de la classe User en lui passant une nouvelle instance de la classe Database
$user = new \Auth\Classes\User(new \Db\Classes\Database);

// Appelle la méthode getChats de l'objet $user pour récupérer les chats de l'utilisateur
$result = $user->getChats($userId);

// Encode le résultat en JSON et l'envoie comme réponse
echo json_encode($result);
