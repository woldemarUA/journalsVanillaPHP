<?php
require_once __DIR__ . '/../init/autoload.php';

// Vérifie si le formulaire a été soumis
if (isset($_POST["articleForm"])) {
    // Récupère les données du formulaire
    $title = filter_input(INPUT_POST, "title");
    $author = filter_input(INPUT_POST, "author");
    $description = filter_input(INPUT_POST, "description");

    // Gère le téléchargement de l'image
    $fileName = $_FILES['image']['name'];
    $fileTmpName = $_FILES['image']['tmp_name'];
    $fileSize = $_FILES['image']['size'];
    $fileError = $_FILES['image']['error'];

    // Vérifie s'il n'y a pas d'erreur lors du téléchargement
    if ($fileError === 0) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        // Vérifie si l'extension du fichier est autorisée
        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadDirectory = '/storage/img/articlesImages/';
            $uniqueIdentifier = uniqid();
            $newFileName =  $uniqueIdentifier . '.' .  $fileExtension;
            $destinationPath = dirname($_SERVER['DOCUMENT_ROOT']) . $uploadDirectory .  $newFileName;
            // Déplace le fichier téléchargé vers le répertoire de destination
            move_uploaded_file($fileTmpName, $destinationPath);
            $uploadedFilePath = 'images/' .  $newFileName;
            // Prépare les données de l'article pour l'insertion ou la mise à jour
            $articleData = [
                "title" => $title,
                "author" => $author,
                "description" => $description,
                "image" => $uploadedFilePath
            ];
            uploadArticle($articleData, $destinationPath);
        }
    } else if ($fileError === 4) { // Gère le cas où aucun fichier n'est téléchargé
        $articleData = [
            "title" => $title,
            "author" => $author,
            "description" => $description,
        ];
        uploadArticle($articleData);
    }
}

// Fonction pour télécharger l'article
function uploadArticle($articleData, $destinationPath = NULL)
{
    // Crée une instance de la classe Article
    $article = new \Articles\Classes\Article();

    // Ajoute l'ID à $articleData si c'est une modification
    if (isset($_POST['edit'])) $articleData['id'] = (int)$_POST['id'];

    // Décide si l'article doit être inséré ou mis à jour
    $response = isset($_POST['edit']) ? $article->updateArticle($articleData) : $article->insertArticle($articleData);

    // Si une erreur se produit, supprime le fichier téléchargé
    if (isset($response["error"])) {
        if (!empty($destinationPath)) {
            unlink($destinationPath);
        }
    }

    // Stocke la réponse dans une session et redirige l'utilisateur
    $_SESSION['articleAdd'] = $response;
    header("Location: /home");
}
