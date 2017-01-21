<?php
/**
 * @author Rémi Taunay
 * @author Vincent Brebion
 */

namespace modele;

require_once __DIR__."/../vue/Erreur.php";
use vue\Erreur;

require_once __DIR__."/../modele/Plateau.php";

class Jeu {

    /**
     * @var Plateau Le plateau du jeu
     */
    private $board;

    /**
     * @var int Le coup actuel
     */
    private $shotNumber;

    /**
     * @var int L'indice de la prochaine case à colorer
     */
    private $idNextCase;

    /**
     * @var int le nombre maximal autorisé de coups dans la partie, moins un
     */
    private $maxShotNb;

    /**
     * @var bool true si la partie est terminée, false sinon
     */
    private $finished;

    /**
     * @var bool true si la partie est gagnée, false sinon
     */
    private $victory;

    /**
     * Le constructeur de Jeu
     */
    public function __construct() {
        // si l'utilisateur n'est pas connecté on affiche la page d'erreur
        if(!isset($_SESSION['userLogged'])) Erreur::displayUnauth();
        else {
            $this->board = new Plateau(); // sinon on instancie le plateau
            $this->shotNumber = 0; // le nombre de coups joués en début de partie est nul
            $this->idNextCase = 0; // la prochaine case à colorer est la toute première du plateau
            $this->maxShotNb = 9; // le nombre de coups maximum est de 10 (de 0 à 9)
            $this->finished = false; // la partie n'est pas finie (elle vient de commencer)
            $this->victory = false; // la partie n'est pas gagnée (elle vient de commencer)
        }
    }

    /**
     * Méthode qui actualise la vue du jeu
     * @param $color String La couleur à ajouter
     * @return bool True si la rangée n'est pas pleine, false sinon
     */
    public function updateBoard($color) {
        if($this->idNextCase < 4) { // si la rangée en cours n'est pas complète

            // on affiche la couleur passée en paramètre sur la prochaine case à colorer
            $this->board->getTries()[$this->shotNumber]->setCase($this->idNextCase, $color);

            $this->idNextCase++; // on incrémente la prochaine case à colorer

            return true; // on retourne vrai car la rangée n'était pas finie
        }
        return false; // on retourne faux car la rangée est finie
    }

    /**
     * Méthode qui met à jour la correspondance essai du joueur / soluce (à condition que le coup joué soit valide)
     * Un coup est valide si quatre couleurs sont données (aucun case non colorée)
     */
    public function validate() {
        $shot = $this->board->getTries()[$this->shotNumber]->getCases(); // récupère ce qui a été joué

        // si la rangée soumise n'est pas pleine, elle ne peut pas être valide
        // (pas de "mode expert" où l'on peut jouer avec des trous, cf. règles du MM)
        // on ne prend donc en compte le coup que si la rangée soumise est valide
        if(!in_array('lightgrey', $shot)) {

            $answer = $this->board->getSoluce()->getCases(); // récupère la rangée secrète (aka la solution)
            $match = ['todo', 'todo', 'todo', 'todo']; // construction des vérifications

            // on commence par vérifier les pions de bonne couleur et bien placés (noir)
            for($i = 0; $i <= 3; $i++) {
                if($shot[$i] == $answer[$i]) {
                    $match[$i] = 'black';
                    $answer[$i] = 'done'; // elle a été traitée
                }
            }

            // on s'occupe des pions de bonne couleur mais mal placés
            for($i = 0; $i <= 3; $i++) {
                if(($match[$i] == 'todo') && in_array($shot[$i], $answer)) {
                    $match[$i] = 'white';
                    // cet exemplaire de couleur devient indisponible pour ses camarades
                    // mais il n'est pas en face, on ne peut donc pas faire $answer[$i] = '';
                    // on va donc prendre le premier disponible, et le rendre indisponible
                    for($j = 0; $j <= 3; $j++) if($answer[$j] == $shot[$i]) {$answer[$j] = 'done'; break;};
                }
            }

            // on met le reste en gris
            for($i = 0; $i <= 3; $i++) if($match[$i] == 'todo') $match[$i] = 'lightgrey';


            // s'il s'avère que toutes les pastilles de vérification sont noires,
            // alors on actualise le statut de la partie : c'est gagné !
            for($i = 0; $i <= 3; $i++) {
                if($match[$i] == 'black') {
                    $this->victory = true;
                    $this->finished = true; // si on a gagné, le jeu est fini
                }
                else {
                    $this->victory = false;
                    $this->finished = false;
                    break; // si une pastille n'est pas noire la partie n'est pas gagnée
                }
            }

            sort($match); // on ordonne le tableau des vérifications
            $this->board->getTries()[$this->shotNumber]->setVerif($match); // on actualise les vérifications

            $this->idNextCase = 0;
            if($this->shotNumber == $this->maxShotNb) $this->finished = true; // si il ne reste plus de coups, alors le jeu est terminé
            if($this->shotNumber <= 9) $this->shotNumber++; // une tentative a été effectuée, donc on passe au coup suivant
        }
    }

    /**
     * Méthode qui efface la ligne en cours
     */
    public function eraseLine() {
        $line = $this->board->getTries()[$this->shotNumber]; // on récupère la rangée en cours
        $lineCases = $line->getVerif(); // ainsi que ses cases de vérification

        if(!in_array("black", $lineCases) or !in_array("white", $lineCases)) { // si la ligne n'a pas été validée
            $reset = array("lightgrey", "lightgrey", "lightgrey", "lightgrey");

            $line->setCases($reset); // on remet la couleur des cases à grise
            $this->idNextCase = 0; // la prochaine case à être colorée est la première de la rangée en cours
        }
    }

    /**
     * Getter de plateau
     * @return Plateau Le plateau du jeu
     */
    public function getBoard() { return $this->board; }

    /**
     * @return int l'indice du coup actuel dans la grille
     */
    public function getShotNumber() {
        return $this->shotNumber;
    }

    /**
     * @return int le nombre maximal de coups, moins un
     */
    public function getMaxShotNb() {
        return $this->maxShotNb;
    }

    /**
     * @return boolean true si la partie est terminée, false sinon
     */
    public function isFinished() {
        return $this->finished;
    }

    /**
     * @return boolean true si victoire, false si défaite
     */
    public function isVictory() {
        return $this->victory;
    }

}