<?php
/**
 * @author Rémi Taunay
 * @author Vincent Brebion
 */

namespace vue;

class Login {
    /**
     * Méthode qui affiche la demande du pseudo et du mot de passe
     */
    public static function displayLogin() {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <title>Login Mastermind</title>
                <link rel="stylesheet" type="text/css" href="vue/style/css/login.css">
            </head>

            <body>
                <div id="login">
                    <h1>Authentification</h1>

                    <form action="index.php" method="POST" id="form">
                        <input class="ids" type="text" name="pseudo" placeholder="Identifiant"><br/>
                        <input class="ids" type="password" name="password" placeholder="Mot de passe"><br/>

                        <input class="buttons" type="submit">
                        <input class="buttons" type="reset">
                    </form>
                </div>
            </body>
        </html>
        <?php
    }
}