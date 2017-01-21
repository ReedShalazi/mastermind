<?php
/**
 * @author Rémi Taunay
 * @author Vincent Brebion
 */

namespace controleur;

require_once __DIR__."/../vue/Login.php";
use vue\Login;

require_once __DIR__."/../vue/Erreur.php";
use vue\Erreur;

require_once __DIR__."/../modele/Bd.php";
use modele\Bd;

require_once __DIR__."/../controleur/CJeu.php";

class Routeur {
    /**
     * Le constructeur de Routeur
     */
    public function __construct() {
        if(empty($_SESSION)) session_start(); // si le joueur vient d'arriver on crée une session
        $this->routeRequest(); // et on route sa requête au bon endroit
    }

    /**
     * Méthode routant toutes les requêtes entrantes de la page index.php
     */
    public function routeRequest() {
        if(!isset($_SESSION['userLogged'])) { // si le joueur n'est pas enregistré
            if(isset($_POST['pseudo']) && isset($_POST['password'])) // si le pseudo et mdp sont dsponibles
                $this->authentification($_POST['pseudo'], $_POST['password']); // on authentifie le joueur
            else Login::displayLogin(); // sinon on affiche la page de login
        }
        else {
            if(isset($_POST['validate'])) CJeu::validate(); // si le joueur veut valider son coup
            else if(isset($_POST['erase'])) CJeu::eraseLine(); // si le joueur veut effacer sa ligne
            else if(isset($_POST['retry'])) CJeu::startGame(); // si le joueur veut recommencer une partie
            else if(isset($_POST['quit'])) CJeu::genStats(); // si le joueur veut quitter la partie
            else if(isset($_POST['disconnect'])) CJeu::disconnect(); // si le joueur veut se déconnecter du jeu
            else CJeu::contGame(); // sinon on continue la partie
        }
    }

    /**
     * Méthode qui authentifie un utilisateur par son pseudo et son mot de passe
     * On utilise la base de données pour l'authentifier
     * @param $pseudo String Le pseudo du joueur
     * @param $password String Le mot de passe du joueur
     */
    public function authentification($pseudo, $password) {
        $bd = new Bd(); // on crée une nouvelle connexion à la base de données

        // si le joueur est bien enregistré dans la base de données
        if($bd->isPlayerRegistered($pseudo, $password)) {
            $bd->disconnect(); // on se déconnecte de la base de données (plus sur)

            $_SESSION['userLogged'] = true; // on ajoute dans la session que le joueur est bien connecté
            $_SESSION['pseudo'] = $pseudo; // et on enregistre par la même son pseudo dans la session

            CJeu::startGame(); // on commence une nouvelle partie
        }
        else Erreur::displayAuthFail(); // le joueur s'est mal authentifié on affiche la page d'erreur
    }
}