<!DOCTYPE html>
<!-- Déclaration du type de document, essentielle pour assurer une bonne interprétation par les navigateurs. -->

<html lang="en">
<!-- Début du document HTML, avec spécification de la langue utilisée (en pour anglais). -->

<head>
    <!-- Section head contenant les métadonnées du document. -->
    <meta charset="UTF-8">
    <!-- Définition du jeu de caractères pour le contenu, UTF-8 pour une compatibilité universelle. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Métadonnée pour le responsive design, assurant que la page s'adapte aux différents appareils. -->
    <title>BiEnVeNuE</title>
    <!-- Titre du document, affiché dans l'onglet du navigateur. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Lien vers les feuilles de style de Bootstrap via CDN, pour styliser la page avec le framework Bootstrap. -->
</head>

<body>
    <!-- Début du corps du document, contenant le contenu visible de la page. -->

    <div class="container mx-auto my-2">
        <!-- Un conteneur Bootstrap pour centrer le contenu et ajouter une marge. -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <!-- Barre de navigation Bootstrap, responsive et de style clair. -->
            <div class="container-fluid">
                <!-- Conteneur fluide pour une largeur adaptative. -->
                <a class="navbar-brand" href="/home">Vieux Journals</a>
                <!-- Logo ou titre de la navbar, ici "Vieux Journals". -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarToggler" aria-controls="navBarToggler" aria-expanded="false" aria-label="Toggle navigation">
                    <!-- Bouton pour les écrans plus petits, affiche le menu de navigation. -->
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navBarToggler">
                    <!-- Contenu de la navbar qui peut se réduire en un menu hamburger sur petits écrans. -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <!-- Liste des éléments de navigation. -->
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/home">Home</a>
                        </li>
                        <!-- Lien vers la page d'accueil. -->
                        <?php if (isset($_SESSION['is_logged'])) { ?>
                            <!-- Condition PHP pour afficher certains éléments seulement si l'utilisateur est connecté. -->
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/addArticle">Ajout Journal</a>
                            </li>
                        <?php } ?>
                        <li class="nav-item">
                            <!-- Option de connexion ou déconnexion selon l'état de la session de l'utilisateur. -->
                            <?php
                            echo !isset($_SESSION['is_logged']) || !$_SESSION['is_logged'] ? '<a class="nav-link" href="/login">Login</a>' : '<a id="logout" class="nav-link" href="/logout">Logout</a>';
                            ?>
                        </li>
                        <li class="nav-item">
                            <!-- Option d'enregistrement ou accès à la messagerie selon l'état de la session. -->
                            <?php
                            if (!isset($_SESSION['is_logged']) || !$_SESSION['is_logged']) { ?>
                                <a class="nav-link" href="/register">Register</a>
                            <?php } else { ?>
                                <a id="chats" class="nav-link" href="/chat">Messagerie</a>
                            <?php } ?>
                        </li>
                    </ul>
                    <!-- La partie du formulaire de recherche est commentée, mais peut être décommentée pour ajouter une fonction de recherche. -->
                </div>
            </div>
        </nav>
    </div>