<?php
// Inclure les dépendances et initialiser les objets nécessaires
require_once __DIR__ . '/../init/autoload.php';
$art = new \Articles\Classes\Article();
$articles = $art->getArticles();

// Inclure le fichier d'en-tête
include 'header.php';
?>

<div class="container">
    <div class="row">
        <?php if (isset($_SESSION['articleAdd'])) {
            // Afficher les messages de succès ou d'erreur lors de l'ajout d'un article
            if (isset($_SESSION['articleAdd']['error'])) { ?>
                <p class="text-danger col"><?php echo $_SESSION['articleAdd']['error']; ?></p>
                <p class="text-danger col">
                    <?php echo $_SESSION['articleAdd']["details"]; ?>
                </p>
            <?php } else { ?>
                <p class="text-success">
                    Journal ajouté avec succès
                </p>
        <?php }
            // Supprimer les informations de session une fois affichées
            unset($_SESSION['articleAdd']);
        } ?>

        <?php if (isset($_SESSION['articleDeleted'])) {
            // Afficher les messages de succès ou d'erreur lors de la suppression d'un article
            if (isset($_SESSION['articleDeleted']['error'])) { ?>
                <p class="text-danger col"><?php echo $_SESSION['articleDeleted']['error']; ?></p>
                <p class="text-danger col">
                    <?php echo $_SESSION['articleDeleted']["details"]; ?>
                </p>
            <?php } else { ?>
                <p class="text-success">
                    Journal supprimé avec succès
                </p>
        <?php }
            // Supprimer les informations de session une fois affichées
            unset($_SESSION['articleDeleted']);
        } ?>
    </div>
    <div class="row">
        <table class="table table-hover caption-top">
            <caption>Liste des Journals</caption>
            <thead>
                <tr>
                    <th scope="col">Titre</th>
                    <th scope="col">Image</th>
                    <th scope="col">Auteur</th>
                    <th scope="col">Description</th>
                    <?php if (isset($_SESSION['is_logged'])) { ?>
                        <th scope="col">Gérer les articles</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Boucler sur chaque article et afficher ses détails
                foreach ($articles as $article) { ?>
                    <tr data-href="article?id=<?php echo $article['id']; ?>">
                        <?php $_SESSION['artId'] = $article['id']; ?>
                        <td scope="row"><?php echo  $article["title"]; ?></td>
                        <td><img src="<?php echo $article['image']; ?>" class="img-thumbnail" style="height: 100px" alt="" srcset=""></td>
                        <td><?php echo  $article["author"]; ?></td>
                        <td><?php echo  $article["description"]; ?></td>
                        <?php if (isset($_SESSION['is_logged'])) { ?>
                            <td class="actions">
                                <a href="addArticle?edit=true&id=<?php echo $article["id"]; ?> " class="btn btn-outline-warning">Éditer</a>
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Effacer</button>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (isset($_SESSION['is_logged'])) { ?>
    <!-- Modal de confirmation pour la suppression d'un article -->
    <div id="deleteModal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Êtes-vous sûr(e)?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Cette action ne peut pas être annulée.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <a href="/deleteArt" type="button" class="btn btn-outline-danger">Confirmer</a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Inclure jQuery et ajouter un script pour gérer le clic sur les lignes du tableau -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        // Ajouter un événement clic à toutes les lignes du tableau
        $('tr[data-href]').on('click', function() {
            window.location = $(this).data('href');
        });

        // Prévenir la propagation de l'événement clic à la ligne lorsque la dernière cellule est cliquée
        $('.actions, .actions *').click(function(e) {
            e.stopPropagation();
        });
    });
</script>

<?php include 'footer.php'; ?>