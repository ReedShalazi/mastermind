<?php
/**
 * @author Rémi Taunay
 * @author Vincent Brebion
 */

namespace controleur;

require_once __DIR__."/../vue/VJeu.php";
use vue\VJeu;

require_once __DIR__."/../vue/VJeuFini.php";
use vue\VJeuFini;

require_once __DIR__."/../modele/Jeu.php";
use modele\Jeu;

require_once __DIR__."/../modele/Bd.php";
use modele\Bd;

require_once __DIR__."/../modele/StatistiqueG.php";
use modele\StatistiqueG;

require_once __DIR__."/../controleur/Routeur.php";

class CJeu {
    /**
     * Méthode qui commence une nouvelle partie
     */
    public static function startGame() {
        $_SESSION['jeu'] = new Jeu(); // on enregistre une nouvelle partie dans la session en cours
        VJeu::displayGame($_SESSION['jeu']->getBoard()); // on affiche le plateau de la partie
    }

    /**
     * Méthode qui continue la partie en cours
     */
    public static function contGame() {
        // si une partie est en cours et que le joueur à envoyé une couleur
        if(isset($_SESSION['jeu']) && isset($_GET['color'])) {
            // on construit un tableau de vérification pour savoir si la couleur envoyée est correcte
            $colors = array("white", "yellow", "orange", "red", "fuchsia", "purple", "green", "blue");

            // si elle est correcte et que le plateau peut être modifié en conséquence
            if(in_array($_GET['color'], $colors) && ($_SESSION['jeu']->updateBoard($_GET['color'])))
                // on affiche le plateau de la partie
                VJeu::displayGame($_SESSION['jeu']->getBoard());
            else {
                // sinon on affiche le plateau de la partie avec en plus un message d'erreur
                VJeu::displayGame($_SESSION['jeu']->getBoard());
                VJeu::displayMustValidate();
            }
        }
    }

    /**
     * Méthode qui valide une ligne du plateau
     */
    public static function validate() {
        $_SESSION['jeu']->validate(); // on valide le coup ou non

        if(!$_SESSION['jeu']->isFinished()) // la partie n'est pas finie, on affiche le plateau
            VJeu::displayGame($_SESSION['jeu']->getBoard());
        else { // la partie est terminée !
            self::genStats(); // on créé les statistiques de la partie
        }
    }

    /**
     * Génère les statistiques en fin de partie
     */
    public static function genStats() {
        $bd = new Bd(); // on crée une nouvelle connexion à la base de données

        // on caste le booléen de victoire en entier (1 pour une victoire, 0 pour une défaite)
        if($_SESSION['jeu']->isVictory()) $gameResult = 1;
        else $gameResult = 0;

        // on crée les statistiques de la partie
        $statsG = new StatistiqueG($_SESSION['pseudo'], $gameResult, $_SESSION['jeu']->getShotNumber());

        $bd->store($statsG); // que l'on enregistre dans la base de données

        VJeuFini::gameOver(); // on affiche la vue de fin de partie

        // on affiche les statistiques du joueur ainsi que le top cinq
        VJeuFini::displayStats($bd->getPlayerStats($_SESSION['pseudo']), $bd->getTopFive());
    }

    /**
     * Méthode qui efface une ligne du plateau
     */
    public static function eraseLine() {
        $_SESSION['jeu']->eraseLine(); // on efface une ligne de la partie en cours
        VJeu::displayGame($_SESSION['jeu']->getBoard()); // et on affiche le plateau modifié
    }

    /**
     * Méthode pour se déconnecter de la partie
     */
    public static function disconnect() {
        // on termine la partie
        VJeuFini::endOfGame();
    }
}