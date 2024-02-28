<?php
// Création d'une nouvelle instance de la classe Article
$art = new \Articles\Classes\Article();

// Récupération de l'article à éditer si le paramètre 'edit' est présent dans l'URL, sinon initialisation à 0
$article = isset($_GET['edit']) ? $art->getArticles((int)$_GET['id'])[0] : 0;

// Mise à jour de la variable de session 'artId' avec l'ID de l'article en cours d'édition
if (isset($_GET['edit'])) {
    $_SESSION['artId'] = (int)$_GET['id'][0];
}
?>

<?php include 'header.php'; // Inclusion du fichier d'en-tête 
?>

<div class="container">
    <div class="row d-flex justify-content-center">
        <h4> <?php echo $_SESSION['username']; ?> vous êtes en train <?php echo isset($_GET['edit']) ? 'd\'éditer' : "d'ajouter"; ?> l'article</h4>
        <p>Soyez informé que votre article doit être approuvé par l'administrateur</p>
        <form action="/addArt" method="POST" enctype="multipart/form-data">
            <?php if (isset($_GET['edit'])) { ?>
                <input type="hidden" name="edit" value="true">
                <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
            <?php } ?>
            <div class="row">
                <!-- Champ pour le titre de l'article -->
                <div class="input-group input-group-sm mb-2 col-md-4">
                    <span id="titleLabel" class="input-group-text">Titre</span>
                    <input required <?php if (isset($_GET['edit'])) { ?> value="<?php echo htmlspecialchars($article['title']); ?>" <?php } ?> name="title" type="text" class="form-control form-control-sm" id="title" aria-describedby="titleHelp" placeholder="Titre de votre article" autocomplete="off">
                </div>
                <!-- Champ pour l'auteur de l'article -->
                <div class="input-group input-group-sm mb-2 col-md-4">
                    <span id="authorLabel" class="input-group-text">Auteur</span>
                    <input required <?php if (isset($_GET['edit'])) { ?> value="<?php echo htmlspecialchars($article['author']); ?>" <?php } ?> name="author" type="text" class="form-control form-control-sm" id="author" placeholder="Ajoutez l'auteur">
                </div>
                <!-- Affichage de l'image actuelle si en mode édition -->
                <?php if (isset($_GET['edit'])) { ?>
                    <div class="text-center">
                        <img src='<?php echo htmlspecialchars($article["image"]); ?>' class="card-img-top" style="width:300px" alt="Image de l'article">
                        <div class="text-danger">
                            <span>Si vous le souhaitez, vous pouvez modifier l'image ci-dessous</span>
                        </div>
                    </div>
                <?php } ?>
                <!-- Champ pour le téléchargement de l'image de l'article -->
                <div class="input-group input-group-sm mb-2 col-md-4">
                    <input name="image" type="file" class="form-control form-control-sm" id="image" accept="image/png, image/jpeg, image/gif, image/bmp">
                </div>
            </div>
            <!-- Champ pour la description de l'article -->
            <div class="row input-group input-group-sm mb-2">
                <span class="input-group-text">Description</span>
                <textarea required name="description" class="form-control" id="description" rows="5"><?php if (isset($_GET['edit'])) {
                                                                                                            echo htmlspecialchars($article['description']);
                                                                                                        } ?></textarea>
            </div>
            <!-- Bouton de soumission du formulaire -->
            <div class="row">
                <button name="articleForm" type="submit" class="btn btn-outline-success btn-sm my-1 w-100">Submit</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; // Inclusion du fichier de pied de page 
?>