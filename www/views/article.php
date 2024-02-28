<?php
// Tentative de récupération de l'ID de l'article depuis l'URL, sinon défaut à 0
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Redirection vers la page d'accueil si aucun ID valide n'est fourni
if ($id === 0) {
    header("Location: /home");
}

// Stockage de l'ID de l'article dans la session pour un usage ultérieur
$_SESSION['artId'] = $id;

// Création d'une instance de l'objet Article pour récupérer les informations de l'article
$art = new \Articles\Classes\Article();
$article = $art->getArticles($id);
$article = $article[0];
?>
<?php include 'header.php'; // Inclusion du fichier d'en-tête 
?>
<div class="container">
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="card w-100">
                <img src='<?php echo $article["image"]; ?>' class="card-img-top w-100" alt="...">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $article["title"]; ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $article["author"]; ?></h6>
                    <p class="card-text"><?php echo $article["description"]; ?></p>
                    <a href="home" class="btn btn-outline-secondary">Retour à la liste</a>
                    <?php if (isset($_SESSION['is_logged'])) { ?>
                        <a href="addArticle?edit=true&id=<?php echo $article["id"]; ?>" class="btn btn-outline-warning">Editer</a>
                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Supprimer</button>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-2"></div>
    </div>
</div>

<?php if (isset($_SESSION['is_logged'])) { ?>
    <div id="deleteModal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Êtes-vous sûr(e) ?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Cette action ne peut pas être annulée.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <a href="/deleteArt?id=<?php echo $article["id"]; ?>" type="button" class="btn btn-outline-danger">Confirmer</a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php include 'footer.php'; // Inclusion du fichier de pied de page 
?>