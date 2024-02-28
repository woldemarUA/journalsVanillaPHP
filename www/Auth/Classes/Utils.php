<?php

namespace Auth\Classes;

class Utils
{
    /**
     * Hache le mot de passe en utilisant l'algorithme de hachage par défaut.
     *
     * @param string $password Le mot de passe à hacher.
     * @return string Le mot de passe haché.
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Assainit une entrée utilisateur pour prévenir les injections XSS.
     *
     * @param string $data La donnée à assainir.
     * @return string La donnée assainie.
     */
    public static function sanitizeInput($data)
    {
        // Échappe les caractères spéciaux en HTML et supprime les balises HTML et PHP.
        return htmlspecialchars(strip_tags($data));
    }
}
