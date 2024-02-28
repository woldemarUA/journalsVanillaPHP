<?php

namespace Classes;

use PDO;
use PDOException;

class Database implements DatabaseInterface
{
    // Déclaration des propriétés nécessaires pour établir une connexion à la base de données
    private $host;
    private $port;
    private $username;
    private $password;
    private $database;
    private $pdo; // L'objet PDO pour la connexion

    // Constructeur pour initialiser les propriétés et établir la connexion
    public function __construct()
    {
        $this->host = 'db'; // L'hôte de la base de données
        $this->port = 3306; // Le port, MySQL utilise par défaut le port 3306
        $this->username = 'root'; // Le nom d'utilisateur pour se connecter
        $this->password = 'woldemar'; // Le mot de passe pour se connecter
        $this->database = 'journalRev'; // Le nom de la base de données
        $this->connect(); // Appeler la méthode connect pour établir la connexion
    }

    // Méthode pour établir la connexion à la base de données
    public function connect()
    {
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database}"; // La chaîne DSN pour PDO
        try {
            // Création de l'objet PDO pour la connexion à la base de données
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            // Configuration des attributs PDO pour lancer une exception en cas d'erreur
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // En cas d'échec de la connexion, terminer le script et afficher l'erreur
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Méthode pour obtenir l'ID de la dernière insertion
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    // Méthode pour exécuter une requête SQL avec ou sans paramètres
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql); // Préparer la requête SQL

            // Exécuter l'instruction avec les paramètres fournis
            $success = $stmt->execute($params);

            // Selon le type de requête, retourner les données ou le nombre de lignes affectées
            if (preg_match('/^(SELECT|SHOW|DESCRIBE|EXPLAIN)/i', $sql)) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC); // Pour les requêtes de sélection, retourner les résultats
            } else {
                return $success ? $stmt->rowCount() : false; // Pour les modifications, retourner le succès ou le nombre de lignes affectées
            }
        } catch (PDOException $e) {
            // En cas d'erreur lors de l'exécution de la requête, lancer une nouvelle exception
            throw new PDOException("Échec de la requête : " . $e->getMessage());
        }
    }

    // Méthode pour fermer la connexion à la base de données
    public function close()
    {
        $this->pdo = null;
    }

    // Destructeur appelé automatiquement à la fin du script pour fermer la connexion
    public function __destruct()
    {
        $this->close();
    }
}
