<?php

namespace Auth\Classes;

class User
{
    // Déclaration des propriétés de la classe Utilisateur.
    protected $id;
    protected $username;
    protected $password_hash;
    protected $email;
    protected $last_login;
    protected $created_at;
    protected $db;

    // Constructeur de la classe, initialisation avec l'objet base de données.
    function __construct(\Db\Classes\DatabaseInterface $db)
    {
        $this->db = $db;
    }

    // Crée un nouvel utilisateur dans la base de données.
    public function createUser($username, $password, $email)
    {
        // Hachage du mot de passe et assainissement des entrées.
        $password_hash = \Auth\Classes\Utils::hashPassword($password);
        $sanitisedEmail = \Auth\Classes\Utils::sanitizeInput($email);
        $sanitisedUsername = \Auth\Classes\Utils::sanitizeInput($username);

        // Requête SQL pour insérer le nouvel utilisateur.
        $sql = "INSERT INTO User (username, password_hash, email) VALUES (:username, :password_hash, :email)";
        $options = ["username" => $sanitisedUsername, "password_hash" => $password_hash, "email" => $sanitisedEmail];
        try {
            $this->db->query($sql, $options);
            return array("username" => $sanitisedUsername,  "email" => $sanitisedEmail);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    // Récupère les chats de l'utilisateur.
    public function getChats($id)
    {
        // Requête SQL pour sélectionner les chats.
        $sql = "SELECT
                c.chatId,
                c.chatTitle AS chatName,
                cp.userId AS chatInit
            FROM
                Chats c
            JOIN
                ChatParticipants cp ON c.chatId = cp.chatId
            WHERE
                cp.userId = :userId";
        $options = ["userId" => $id];

        try {
            return $this->db->query($sql, $options);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    // Récupère les messages d'un chat spécifique.
    public function getMessages($chatId)
    {
        // Requête SQL pour sélectionner les messages.
        $sql = "SELECT * FROM Messages WHERE chatId = :chatId ORDER BY messageTimeStamp ASC";
        $options = ["chatId" => $chatId];
        try {
            return $this->db->query($sql, $options);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    // Authentifie l'utilisateur.
    public function authenticate($username, $password)
    {
        // Assainissement de l'entrée et sélection de l'utilisateur par son nom d'utilisateur.
        $sanitisedUsername = \Auth\Classes\Utils::sanitizeInput($username);
        $sql = "SELECT * FROM User where username = :username";
        $options = ["username" => $sanitisedUsername];
        try {
            $_SESSION = array(); // Réinitialisation des données de session.
            $response = $this->db->query($sql, $options)[0] ?? null;
            if ($response && password_verify($password, $response["password_hash"])) {
                // Initialisation de la session en cas de succès.
                $_SESSION["user_id"] =  $response["id"];
                $_SESSION["username"] =  $response["username"];
                $_SESSION["is_logged"] = true;
                header("Location:  /dashboard");

                return true;
            } else {
                // Gestion de l'échec de l'authentification.
                $_SESSION['error'] =  'Identifiant ou mot de passe invalide.';
                $_SESSION["is_logged"] = false;
                header("Location: /login");

                return false;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    // Déconnecte l'utilisateur.
    public function logOut()
    {
        // Réinitialisation de la session.
        $_SESSION = array();

        // Suppression du cookie de session si nécessaire.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
            header("Location: /");
        }

        // Destruction de la session.
        session_destroy();
        exit(); // Prévention de l'exécution de code supplémentaire.
    }

    // Getters pour accéder aux propriétés de la classe.
    public function getId()
    {
        return  $this->id;
    }

    public function getUsername()
    {
        return  $this->username;
    }

    public function getEmail()
    {
        return  $this->email;
    }

    public function getLastLogin()
    {
        return  $this->last_login;
    }

    public function getCreatedAt()
    {
        return  $this->created_at;
    }
}
