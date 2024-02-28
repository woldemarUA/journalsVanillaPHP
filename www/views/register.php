<?php
// Inclure l'en-tête de la page
include 'header.php';
?>

<div class="container">
    <?php
    // Afficher les messages d'erreur si la création de l'utilisateur a échoué
    if (isset($_SESSION["userFailed"]) && !empty($_SESSION["userFailed"])) {
        foreach ($_SESSION["userFailed"] as $error) {
            // Utiliser htmlspecialchars pour éviter les failles XSS
            echo "<p class='text-danger'>" . htmlspecialchars($error) . "</p>";
        }
        // Nettoyer les messages d'erreur de la session après les avoir affichés
        unset($_SESSION["userFailed"]);
    }
    ?>

    <!-- Formulaire d'inscription pour les nouveaux utilisateurs -->
    <form action="/reg" method="POST">
        <input type="hidden" name="action" value="auth">
        <div class="form-group">
            <label for="uName">Votre nom d'utilisateur</label>
            <input required name="username" type="text" class="form-control" id="uName" aria-describedby="uNameHelp" placeholder="Tapez votre nom d'utilisateur" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="eMail">Votre email</label>
            <input required name="email" type="email" class="form-control" id="eMail" aria-describedby="eMailHelp" placeholder="Tapez votre email" autocomplete="off">
            <small id="eMailHelp" class="form-text text-muted">Nous ne partagerons jamais votre email avec quiconque.</small>
        </div>
        <div class="form-group">
            <label for="motDepass">Mot de passe</label>
            <input required name="password" type="password" class="form-control" id="motDepass" placeholder="Mot de passe">
        </div>

        <button type="submit" class="btn btn-outline-success btn-sm my-1">S'inscrire</button>
    </form>
</div>

<?php
// Inclure le pied de page de la page
include 'footer.php';
?>