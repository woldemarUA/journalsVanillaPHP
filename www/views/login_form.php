<?php
// Inclure le fichier d'en-tête commun à toutes les pages
include 'header.php';
?>

<div class="container">
    <?php
    // Vérifier si un nouvel utilisateur a été créé avec succès
    if (isset($_SESSION['userCreated'])) { ?>
        <p class="text-success">Utilisateur créé avec succès,<br> vous pouvez vous connecter avec vos informations d'identification.</p>
        <p> Nom d'utilisateur : <?php echo $_SESSION['userCreated']["username"]; // Afficher le nom d'utilisateur créé 
                                ?> </p>
    <?php } else if (isset($_SESSION['error']) || isset($_SESSION["is_logged"])) { // Si une erreur s'est produite ou si l'utilisateur est déjà connecté 
    ?>
        <p class="text-danger"><?php echo $_SESSION['error']; ?><br> Essayez-vous encore une fois.</p>
    <?php } ?>
    <form action="/auth" method="POST">
        <input type="hidden" name="action" value="auth"> <!-- Champ caché pour déterminer l'action du formulaire -->
        <div class="form-group">
            <label for="uName">Votre nom d'utilisateur</label> <!-- Étiquette pour le champ du nom d'utilisateur -->
            <input required name="username" type="text" class="form-control" id="uName" aria-describedby="uNameHelp" placeholder="Tapez votre nom d'utilisateur" autocomplete="off">
            <!-- Champ de saisie pour le nom d'utilisateur avec validation côté client -->
        </div>
        <div class="form-group">
            <label for="motDepass">Mot de passe</label> <!-- Étiquette pour le champ du mot de passe -->
            <input required name="password" type="password" class="form-control" id="motDepass" placeholder="Mot de passe">
            <!-- Champ de saisie pour le mot de passe avec validation côté client -->
        </div>

        <button type="submit" class="btn btn-outline-success btn-sm my-1">Connexion</button> <!-- Bouton pour soumettre le formulaire -->
    </form>
</div>

<?php include 'footer.php' // Inclure le fichier de pied de page commun à toutes les pages 
?>