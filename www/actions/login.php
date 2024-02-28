<?php
// Inclut le fichier d'initialisation qui charge automatiquement toutes les classes nécessaires
require_once __DIR__ . '/../init/autoload.php';

// Vérifie si la requête est de type POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoie et récupère le nom d'utilisateur à partir des données envoyées par le formulaire
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    // Récupère le mot de passe directement à partir des données POST (il n'est pas nécessaire de le nettoyer car il sera hashé)
    $password = $_POST['password'] ?? '';

    // Crée une nouvelle instance de la classe User, en passant une nouvelle instance de Database
    $user = new \Auth\Classes\User(new \Db\Classes\Database());

    // Appelle la méthode authenticate pour vérifier les informations de connexion de l'utilisateur
    $user->authenticate($username, $password);
}
