<?php
/**
 * @author Rémi Taunay
 * @author Vincent Brebion
 */

namespace vue;

class Erreur {
    public static function displayAuthFail() {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>Erreur login incorrect</title>
            </head>

            <body>
                <h1>Mauvaise authentification</h1>

                <p>Le pseudo et/ou mot de passe est/sont invalide(s)</p>
                <a href="index.php">Retour</a>
            </body>
        </html>
        <?php
    }

    public static function displayUnauth() {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Erreur accès incorrect</title>
        </head>

        <body>
        <h1>Pas Authentifié</h1>

        <p>Vous n'êtes pas enregistré en tant qu'utilisateur</p>
        <a href="index.php">Retour</a>
        </body>
        </html>
        <?php
    }
}