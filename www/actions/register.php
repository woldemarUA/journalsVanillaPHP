<?php

// Inclut le fichier d'initialisation pour charger automatiquement les classes nécessaires
require_once __DIR__ . '/../init/autoload.php';

// Crée une nouvelle instance de User, en utilisant une nouvelle instance de Database pour la gestion des données
$user = new \Auth\Classes\User(new \Db\Classes\Database());

// Vérifie si la requête est de type POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère et nettoie les données d'entrée
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // Le mot de passe pourrait nécessiter un traitement supplémentaire pour la sécurité

    // Initialisation d'un tableau pour stocker les erreurs de validation
    $errors = [];

    // Valide le format de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format d'email invalide";
    }

    // Si aucune erreur de validation, tente de créer l'utilisateur
    if (empty($errors)) {
        try {
            // Appelle la méthode createUser et redirige en cas de succès
            $result = $user->createUser($username, $password, $email);

            // Stocke le résultat dans la session et redirige vers la page de connexion
            $_SESSION['userCreated'] = $result;
            header('Location: /public/index.php?page=login');
            exit;
        } catch (\Exception $e) {
            // Gère les exceptions en cas d'erreur lors de la création de l'utilisateur
            $_SESSION["userFailed"] = $errors;
            header('Location: /public/index.php?page=register');
            exit;
        }
    } else {
        // Affiche les erreurs de validation
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
