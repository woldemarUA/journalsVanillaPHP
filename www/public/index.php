<?php
// Démarrage de la session
session_start();
// Inclusion de l'autoloader pour la gestion des dépendances
require_once __DIR__ . '/../init/autoload.php';

// Définition des routes protégées nécessitant une authentification
$protectedRoutes = ['deleteArt', 'dashboard', 'chat', 'logout', 'fetchUserId, usersList, getChats', 'getMessages, addArticle, addArt'];

// Récupération de la page demandée via GET ou l'action via POST ou GET
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else if (isset($_POST['action']) || isset($_GET['action'])) {
    $page = $_POST['action'] ?? $_GET['action'] ?? '';
} else {
    // Page par défaut si aucune autre n'est spécifiée
    $page = "home";
}

// Redirection vers la page de connexion si l'utilisateur n'est pas connecté et tente d'accéder à une route protégée
if (in_array($page, $protectedRoutes) && !isset($_SESSION['is_logged'])) {
    header('Location: /public/index.php?page=login');
    exit;
}

// Routage vers la page ou l'action demandée
switch ($page) {
    case 'home':
        include(__DIR__ . '/../views/home.php');
        break;
    case 'login':
        include(__DIR__ . '/../views/login_form.php');
        break;
    case 'logout':
        require(__DIR__ . '/../actions/logout.php');
        break;
    case 'dashboard':
        include(__DIR__ . '/../views/dashboard.php');
        break;
    case 'article':
        include(__DIR__ . '/../views/article.php');
        break;
    case 'logerror':
        include(__DIR__ . '/../views/logerror.php');
        break;
    case 'chat':
        include(__DIR__ . '/../views/chat.php');
        break;
    case 'addArticle':
        include(__DIR__ . '/../views/articleForm.php');
        break;
    case 'deleteArt':
        include(__DIR__ . '/../actions/deleteArt.php');
        break;
    case 'register':
        include(__DIR__ . '/../views/register.php');
        break;
    case 'auth':
        require(__DIR__ . '/../actions/login.php');
        break;
    case 'fetchUserId':
        require(__DIR__ . '/../actions/fetchUserId.php');
        break;
    case 'getChats':
        require(__DIR__ . '/../actions/getChats.php');
        break;
    case 'addArt':
        require(__DIR__ . '/../actions/addArt.php');
        break;
    case 'getMessages':
        require(__DIR__ . '/../actions/getMessagesChat.php');
        break;
    case 'usersList':
        require(__DIR__ . '/../actions/usersList.php');
        break;
    case 'reg':
        require(__DIR__ . '/../actions/register.php');
        break;
    default:
        // Page ou action non trouvée
        echo "404 Not Found";
        break;
}
