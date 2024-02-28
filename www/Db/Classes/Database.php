<?php

namespace Db\Classes;

use PDO;
use PDOException;

// Définit une classe de base de données pour gérer la connexion et les requêtes à la base de données
class Database implements DatabaseInterface
{
    private $host;
    private $username;
    private $password;
    private $database;
    private $pdo;

    // Constructeur de la classe, initialise les paramètres de connexion et établit la connexion
    public function __construct()
    {
        $this->host = 'db';
        $this->username = 'journals';
        $this->password = 'journals';
        $this->database = 'journalRev';
        $this->connect();
    }

    // Méthode pour établir une connexion à la base de données
    public function connect()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->database}";
        try {
            // Tente de se connecter à la base de données avec les paramètres fournis
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            // Définit le mode de gestion des erreurs sur Exception pour faciliter le débogage
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // En cas d'échec de la connexion, arrête le script et affiche un message d'erreur
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Méthode pour récupérer l'ID de la dernière insertion
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    // Méthode pour exécuter une requête SQL avec des paramètres optionnels
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);

            // Exécute la requête préparée avec les paramètres fournis
            $success = $stmt->execute($params);

            // Vérifie le type de requête pour déterminer le type de résultat à retourner
            if (preg_match('/^(SELECT|SHOW|DESCRIBE|EXPLAIN)/i', $sql)) {
                // Pour les requêtes de sélection, retourne les résultats
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Pour les autres types de requêtes (INSERT, UPDATE, DELETE), retourne le statut de succès
                return $success ? $stmt->rowCount() : false;
            }
        } catch (PDOException $e) {
            // En cas d'erreur lors de l'exécution de la requête, lance une exception
            throw new PDOException("Échec de la requête : " . $e->getMessage());
        }
    }

    // Méthode pour fermer la connexion à la base de données
    public function close()
    {
        $this->pdo = null;
    }

    // Destructeur de la classe, appelle la méthode close pour s'assurer que la connexion est fermée
    public function __destruct()
    {
        $this->close();
    }
}
