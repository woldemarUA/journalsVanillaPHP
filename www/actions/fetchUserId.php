<?php
// Définit le type de contenu attendu pour la réponse comme JSON
header('Content-Type: application/json');

// Encode et renvoie les informations de l'utilisateur en JSON, en utilisant l'opérateur de coalescence nulle
// pour retourner `null` si les clés 'user_id' ou 'username' ne sont pas trouvées dans $_SESSION
echo json_encode([
    'userId' => $_SESSION['user_id'] ?? null, // Retourne l'ID de l'utilisateur stocké dans la session, ou null si non défini
    'username' => $_SESSION['username'] ?? null, // Retourne le nom d'utilisateur stocké dans la session, ou null si non défini
]);
