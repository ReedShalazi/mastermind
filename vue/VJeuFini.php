<?php
/**
 * @author Rémi Taunay
 * @author Vincent Brebion
 */

namespace vue;

use modele\StatistiqueP;

class VJeuFini {
    /**
     * Méthode qui affiche le résultat en fin de partie
     */
    public static function gameOver() {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="utf-8">
                <title>Fin du jeu</title>
                <link rel="stylesheet" type="text/css" href="vue/style/css/jeuFini.css">
                <link rel="stylesheet" type="text/css" href="vue/style/css/jeu.css">
            </head>

            <body>
                <div id="jeuFini">
                    <?php
                    $shotNumber = $_SESSION['jeu']->getShotNumber(); // on récupère le nombre de coup(s)

                    if($_SESSION['jeu']->isVictory())
                        echo "<h2>Bravo ".$_SESSION['pseudo']." vous avez gagné la partie en ".$shotNumber." coup(s)</h2>";
                    else echo "<h2>Dommage ".$_SESSION['pseudo']." vous avez perdu la partie en ".$shotNumber." coup(s)</h2>";
                    ?>

                    <h3>La solution était:</h3>

                    <table id="solution"> <!-- Plateau de la soluce -->
                        <?php
                        for($i = 0; $i < 4; $i++) {
                            echo "<td style=\"background-color: ";
                            echo $_SESSION['jeu']->getBoard()->getSoluce()->getCases()[$i] . ";\">"; // on affiche la solution
                            echo "<div></div></td>";
                        }
                        ?>
                    </table>

                    <br>

                    <table id="plateau"> <!-- Plateau du jeu -->
                        <?php
                        for($i = 0; $i < $_SESSION['jeu']->getShotNumber(); $i++) { // on affiche les coups jouées
                            echo "<tr> <!-- Une rangee du plateau -->";

                            for($j = 0; $j < 4; $j++) {
                                echo "<td style=\"background-color:";
                                echo $_SESSION['jeu']->getBoard()->getTries()[$i]->getCases()[$j].";\">"; // itération des cases
                                echo "<div></div>";
                                echo "</td> <!-- Une case de la rangee -->";
                            }

                            echo "<td id=\"verif\">";
                            echo "<table>";
                            echo "<tr> <!-- La case des vérif' -->";

                            for($k = 0; $k < 4; $k++) {
                                echo "<td style=\"background-color:";
                                echo $_SESSION['jeu']->getBoard()->getTries()[$i]->getVerif()[$k].";\">"; // itération des vérif'
                                echo "<a href='index.php'></a>";
                                echo "</td> <!-- Une vérification -->";
                            }

                            echo "</tr>";
                            echo "</table>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
        <?php
        VJeu::actionsEndGame(); // on affiche les boutons rejouer et déconnecter
    }

    /**
     * Méthode permettant d'afficher les différentes statistiques
     * sur le joueur, ainsi que le top 5 des meilleurs joueurs
     * @param $playerStats StatistiqueP Les statistiques sur le joueur
     * @param $topFivePlayers array Le top 5 des meilleurs joueurs
     */
    public static function displayStats($playerStats, $topFivePlayers) {
        ?>
                    <br>

                    <table class="stats">
                        <tr>
                            <th>Joueur</th>
                            <th>Parties gagnées</th>
                            <th>Parties jouées</th>
                            <th>Nombre de coups pour gagner</th>
                        </tr>
                    <?php
                        echo "<tr><td>".$playerStats->getPseudo()."</td>"; // on affiche les stats du joueur
                        echo "<td>".$playerStats->getNbPartiesGagnees()."</td>";
                        echo "<td>".$playerStats->getNbParties()."</td>";
                        echo "<td>".$playerStats->getNbCoupsPourGagner()."</td></tr>";
                    ?>

                    </table>

                    <br>

                    <p><b>Le top cinq des meilleurs joueurs</b></p>
                    <table class="stats">
                        <tr>
                            <th>Joueur</th>
                            <th>Parties gagnées</th>
                            <th>Parties jouées</th>
                            <th>Nombre de coups pour gagner</th>
                        </tr>

                    <?php
                        foreach($topFivePlayers as $topPlayer) { // on affiche les stats du top cinq
                            echo "<tr><td>".$topPlayer->getPseudo()."</td>";
                            echo "<td>".$topPlayer->getNbPartiesGagnees()."</td>";
                            echo "<td>".$topPlayer->getNbParties()."</td>";
                            echo "<td>".$topPlayer->getNbCoupsPourGagner()."</td></tr>";
                        }
                    ?>
                    </table>
                </div>
            </body>
        </html>
        <?php
    }

    /**
     * Méthode permettant de revenir à la page index.php
     * (efface la variable $_SESSION)
     */
    public static function endOfGame() {
        session_destroy(); // on termine la session pour repartir proprement
        header("Location: index.php"); // on redirige vers la page index.php
    }
}