<?php
require_once __DIR__ . '/../init/autoload.php';
// Ce script vérifie si un identifiant d'article est présent dans la session. 
// Si oui, il procède à la suppression de l'article correspondant à cet identifiant en utilisant 
// la méthode deleteArticle de la classe Article. 
// Ensuite, il stocke la réponse de cette opération dans la session et redirige l'utilisateur vers la page d'accueil.
// Vérifie si un identifiant d'article est stocké dans la session
if (isset($_SESSION['artId'])) {
    // Crée une nouvelle instance de l'article
    $article = new \Articles\Classes\Article;

    // Appelle la fonction pour supprimer l'article utilisant l'identifiant stocké dans la session
    $response = $article->deleteArticle($_SESSION['artId']);

    // Stocke la réponse de la suppression dans la session
    $_SESSION['articleDeleted'] = $response;

    // Redirige l'utilisateur vers la page d'accueil après la suppression
    header("Location: /home");
}
