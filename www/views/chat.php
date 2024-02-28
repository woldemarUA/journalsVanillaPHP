<?php
// Inclure le fichier d'en-tête commun
include 'header.php';
?>

<div class="container">
    <h4><span class="text-success"><?php echo $_SESSION['username']; ?></span>, vos discussions</h4>
    <ul class="nav nav-tabs" id="chatTab" role="tablist">
        <li class="nav-item" role="presentation">
            <!-- Onglet pour les discussions actives -->
            <button class="nav-link active" id="active-chat-tab" data-bs-toggle="tab" data-bs-target="#active-chat" type="button" role="tab" aria-controls="active-chat" aria-selected="true">Discussions actives</button>
        </li>
        <li class="nav-item" role="presentation">
            <!-- Onglet pour démarrer une nouvelle discussion -->
            <button class="nav-link" id="new-chat-tab" data-bs-toggle="tab" data-bs-target="#new-chat" type="button" role="tab" aria-controls="new-chat" aria-selected="false">Démarrer une nouvelle discussion</button>
        </li>
        <!-- Vous pouvez ajouter d'autres onglets ici si nécessaire -->
    </ul>
    <div class="tab-content" id="chatTabContent">
        <div class="tab-pane fade show active" id="active-chat" role="tabpanel" aria-labelledby="active-chat-tab">
            <div class="row">
                <div class="col-4">
                    <!-- Liste des discussions actives -->
                    <div class="list-group" id="chat-list">
                        <!-- Les discussions seront chargées ici -->
                    </div>
                </div>
                <div class="col-8">
                    <!-- Corps de la discussion active -->
                    <div id="chat-body" class=" h-50" style="overflow-x: hidden; overflow-y: auto;">
                        <!-- Les messages de la discussion active seront chargés ici -->
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="new-chat" role="tabpanel" aria-labelledby="new-chat-tab">
            <!-- Liste des utilisateurs pour démarrer une nouvelle discussion -->
            <div class="list-group list-group-flush" id="new-chat-user-list">
                <!-- Les utilisateurs seront chargés ici -->
            </div>
        </div>
        <!-- Ici, vous pouvez ajouter d'autres contenus d'onglet si nécessaire -->
    </div>
</div>

<?php
// Inclure le fichier de pied de page commun
include 'footer.php';
?>