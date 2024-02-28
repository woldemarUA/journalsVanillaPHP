<?php

// Inclut le fichier d'initialisation pour charger automatiquement les classes nécessaires
require_once __DIR__ . '/../init/autoload.php';

// Récupère l'identifiant de l'utilisateur actuellement connecté depuis la session
$userId = $_SESSION['user_id'];

// Crée une nouvelle instance de la classe Database pour interagir avec la base de données
$db = new \Db\Classes\Database();

// Prépare la requête SQL pour sélectionner les id, username, et email de tous les utilisateurs sauf l'utilisateur actuel
$sql = 'SELECT id, username, email FROM User WHERE id != :userId';

// Prépare les paramètres à lier à la requête SQL
$params = ["userId" => $userId];

// Exécute la requête et récupère le résultat
$result = $db->query($sql, $params);

// Définit le type de contenu de la réponse comme JSON
header('Content-Type: application/json');

// Encode le résultat en JSON et l'envoie comme réponse
echo json_encode($result);
