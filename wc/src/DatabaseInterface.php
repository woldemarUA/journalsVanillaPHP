<?php

namespace Classes;

// Définition de l'interface DatabaseInterface.
interface DatabaseInterface
{
    // Méthode pour établir une connexion à la base de données.
    // Toute classe qui implémente cette interface doit fournir une implémentation de cette méthode.
    public function connect();

    // Méthode pour exécuter une requête SQL sur la base de données.
    // Prend en paramètre une chaîne de caractères représentant la requête SQL ($sql) et un tableau optionnel de paramètres ($params) à utiliser avec la requête.
    // Toute classe qui implémente cette interface doit fournir une implémentation de cette méthode.
    public function query($sql, $params = []);

    // Méthode pour fermer la connexion à la base de données.
    // Toute classe qui implémente cette interface doit fournir une implémentation de cette méthode.
    public function close();
}
