<?php
/**
 * @author Rémi Taunay
 * @author Vincent Brebion
 */

namespace modele;

class StatistiqueG {
    /**
     * @var String Le pseudo du joueur
     */
    private $pseudo;

    /**
     * @var int 1 si la partie est gagnée, 0 sinon
     */
    private $partieGagnee;

    /**
     * @var int Le nombre de coups joués
     */
    private $nombreCoups;

    /**
     * Le constructeur de StatistiqueG (stats des parties)
     * @param $pseudo String le pseudo du joueur
     * @param $partieGagnee int 1 si la partie a été gagnée, 0 sinon
     * @param $nombreCoups int nombre de coups joués lors de cette partie
     */
    public function __construct($pseudo, $partieGagnee, $nombreCoups) {
        // les statistiques pour une partie se composent du pseudo du joueur, du résultat de la partie
        // (victoire ou non) et du nnombre de coups joués

        $this->pseudo = $pseudo;
        $this->partieGagnee = $partieGagnee;
        $this->nombreCoups = $nombreCoups;
    }

    /**
     * @return String
     */
    public function getPseudo() { return $this->pseudo; }

    /**
     * @return boolean
     */
    public function getPartieGagnee() { return $this->partieGagnee; }

    /**
     * @return int
     */
    public function getNombreCoups() { return $this->nombreCoups; }
}