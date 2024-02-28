<?php

namespace Articles\Classes;

class Article
{
    protected $id;
    protected $title;
    protected $author;
    protected $description;
    protected $image;
    protected $created_at;
    protected $approved;

    protected $db;

    // Constructeur : initialise la connexion à la base de données
    public function __construct()
    {
        $this->db = new \Db\Classes\Database;
    }

    // Met à jour un article dans la base de données
    public function updateArticle($articleData)
    {
        $sql = '';

        // Si une image est fournie, inclut la colonne image dans la mise à jour
        if (isset($articleData['image'])) {
            $sql = "UPDATE Article SET title = :title, author = :author, description = :description, image = :image WHERE id = :id";
        } else {
            // Si aucune image n'est fournie, ne met pas à jour la colonne image
            $sql = "UPDATE Article SET title = :title, author = :author, description = :description WHERE id = :id";
        }
        try {
            $this->db->query($sql, $articleData);
            $lastInsertedId = $this->db->lastInsertId();
            return ["msg" => "Article modifié avec succès", "articleid" => $lastInsertedId];
        } catch (\PDOException $e) {
            return ["error" => "L'article n'a pas pu être modifié", "details" => $e->getMessage(), "article" => $articleData];
        }
    }

    // Supprime un article de la base de données
    public function deleteArticle($id)
    {
        $sql = 'DELETE FROM Article WHERE id=:id';
        $options = ["id" => $id];
        try {
            $this->db->query($sql, $options);
            return ["msg" => "Article supprimé avec succès"];
        } catch (\PDOException $e) {
            return ["error" => "L'article n'a pas pu être supprimé", "details" => $e->getMessage()];
        }
    }

    // Insère un nouvel article dans la base de données
    public function insertArticle($articleData)
    {
        $sql = '';

        if (isset($articleData['image'])) {
            $sql = "INSERT INTO Article (title, author, description, image) VALUES (:title ,:author, :description, :image)";
        } else {
            $sql = "INSERT INTO Article (title, author, description) VALUES (:title ,:author, :description)";
        }
        try {
            $this->db->query($sql, $articleData);
            $lastInsertedId = $this->db->lastInsertId();
            return ["msg" => "Article ajouté avec succès", "articleid" => $lastInsertedId];
        } catch (\PDOException $e) {
            return ["error" => "L'article n'a pas pu être ajouté", "details" => $e->getMessage()];
        }
    }

    // Récupère un ou plusieurs articles de la base de données
    public function getArticles($id = 0)
    {
        $sql = $id != 0 ? "SELECT * FROM Article WHERE id=:id" : "SELECT * FROM Article";
        try {
            $result = $this->db->query($sql, $id != 0 ? ["id" => $id] : []);
            return $result;
        } catch (\PDOException $e) {
            return ["error" => "Erreur lors de la récupération des articles", "details" => $e->getMessage()];
        }
    }

    // Les getters et setters suivants permettent d'accéder et de modifier les propriétés de l'article

    // Setters protégés (Utilisés à l'interne de la classe, pas accessible publiquement)
    protected function setId($id)
    {
        $this->id = $id;
    }
    protected function setTitle($title)
    {
        $this->title = $title;
    }
    protected function setAuthor($author)
    {
        $this->author = $author;
    }
    protected function setDescription($description)
    {
        $this->description = $description;
    }
    protected function setImage($image)
    {
        $this->image = $image;
    }
    protected function setApproved($approved)
    {
        $this->approved = $approved;
    }
    protected function setCreatedAt($date)
    {
        $this->created_at = $date;
    }

    // Getters publiques
    public function getId()
    {
        return $this->id;
    }
    public function getAuthor()
    {
        return $this->author;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getImage()
    {
        return $this->image;
    }
    public function getApproved()
    {
        return $this->approved;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
