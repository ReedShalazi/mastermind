<?php
/**
 * @author Rémi Taunay
 * @author Vincent Brebion
 */

namespace vue;

require_once __DIR__."/../vue/Erreur.php";

require_once __DIR__."/../modele/Plateau.php";
use modele\Plateau;

class VJeu {
    /**
     * Méthode qui affiche le plateau passé en paramètre
     * @param $plateau Plateau Le plateau à afficher
     */
    public static function displayGame($plateau) {
        if(!isset($_SESSION['userLogged'])) Erreur::displayUnauth();
        else {
            ?>
            <!DOCTYPE html>
            <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>Mastermind</title>
                    <link rel="stylesheet" href="vue/style/css/jeu.css">
                    <script type="text/javascript"></script>
                </head>

                <body>
                    <div id="jeu">
                        <h2>Bienvenue <?php if(isset($_SESSION['pseudo'])) echo $_SESSION['pseudo']; ?></h2>

                        <h3><?php echo "Il vous reste ".(10 - $_SESSION['jeu']->getShotNumber())." coups à jouer"; ?></h3>

                        <table id="plateau"> <!-- Plateau du jeu -->
                            <?php
                            for($i = 0; $i < 10; $i++) {
                                echo "<tr> <!-- Une rangee du plateau -->";

                                for($j = 0; $j < 4; $j++) {
                                    echo "<td style=\"background-color:";
                                    echo $plateau->getTries()[$i]->getCases()[$j].";\">"; // itération des cases
                                    echo "<div></div>";
                                    echo "</td> <!-- Une case de la rangee -->";
                                }

                                echo "<td id=\"verif\">";
                                echo "<table>";
                                echo "<tr> <!-- La case des vérif' -->";

                                for($k = 0; $k < 4; $k++) {
                                    echo "<td style=\"background-color:";
                                    echo $plateau->getTries()[$i]->getVerif()[$k].";\">"; // itération des vérif'
                                    echo "<a href='index.php'></a>";
                                    echo "</td> <!-- Une vérification -->";
                                }

                                echo "</tr>";
                                echo "</table>";
                                echo "</tr>";
                            }
                            ?>
                        </table>

                        <br>

                        <table id="couleurs"> <!-- Plateau des couleurs possibles -->
                            <tr>
                                <?php
                                    $colors = array("white", "yellow", "orange", "red", "fuchsia", "purple", "green", "blue");

                                    foreach($colors as $color) {
                                        echo "<td style=\"background-color:".$color."\">";
                                        echo "<a href=\"index.php?color=".$color."\"></a>";
                                        echo "</td>";
                                    }
                                ?>
                            </tr>
                        </table>

                        <?php
                            self::actions();
                        ?>
                    </div>
                </body>
            </html>
            <?php
        }
    }

    /**
     * Méthode qui affiche les possibilités pendant une partie
     * Les possibilités sont:
     *      1) Valider le coup
     *      2) Effacer la ligne
     *      3) Quitter la partie
     */
    public static function actions() {
        echo "<br>";
        echo "<form action=\"index.php\" method=\"post\">";
        echo "<input class=\"buttons\" type=\"submit\" name=\"validate\" value=\"Valider\">";
        echo "<input class=\"buttons\" type=\"submit\" name=\"erase\" value=\"Effacer\">";
        echo "<input class=\"buttons\" type=\"submit\" name=\"quit\" value=\"Quitter\">";
        echo "</form>";
    }

    /**
     * Méthode qui affiche les possibilités à la fin d'une partie
     * Les possibilités sont:
     *      1) Recommencer une partie
     *      2) Se déconnecter
     */
    public static function actionsEndGame() {
        echo "<br>";
        echo "<form action=\"index.php\" method=\"post\">";
        echo "<input class=\"buttons\" type=\"submit\" name=\"retry\" value=\"Rejouer\">";
        echo "<input class=\"buttons\" type=\"submit\" name=\"disconnect\" value=\"Déconnexion\">";
        echo "</form>";
    }

    /**
     * Méthode qui affiche un message à l'utilisateur suite à une manipulation
     * incorrecte lors de la partie (utilise du javascript)
     */
    public static function displayMustValidate() {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
            <script type="text/javascript">
                function warning() { alert("Vous devez absolument valider ou effacer avant de continuer"); }
            </script>

            <body onload="warning()"></body>
        </html>
        <?php
    }
}