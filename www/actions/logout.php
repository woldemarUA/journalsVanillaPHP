<?php
// Inclut le fichier d'initialisation qui charge automatiquement toutes les classes nécessaires
require_once __DIR__ . '/../init/autoload.php';

// Crée une nouvelle instance de la classe User, en passant une nouvelle instance de la classe Database
$user = new \Auth\Classes\User(new \Db\Classes\Database());

// Appelle la méthode logOut pour déconnecter l'utilisateur
$user->logOut();
