<?php

namespace Db\Classes;

// Définit une interface pour les classes de base de données
interface DatabaseInterface
{
    // Méthode pour établir une connexion à la base de données
    public function connect();

    // Méthode pour exécuter une requête SQL avec des paramètres optionnels
    public function query($sql, $params = []);

    // Méthode pour fermer la connexion à la base de données
    public function close();
}
