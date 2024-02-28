<?php

// Enregistre une fonction d'auto-chargement pour les classes
spl_autoload_register(function ($className) {
    // Définit le répertoire de base pour le préfixe de l'espace de noms comme étant la racine du projet
    $base_dir = __DIR__ . '/../';

    // Utilise l'espace de noms pour déterminer le chemin du répertoire correct, incluant le sous-répertoire
    $class_path = str_replace('\\', '/', $className) . '.php';

    // Vérifie si le fichier existe et l'inclut
    $file = $base_dir . $class_path;
    if (file_exists($file)) {
        require_once $file;
    }
});
