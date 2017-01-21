<?php
/**
 * @author Rémi Taunay
 * @author Vincent Brebion
 */

namespace modele;

class StatistiqueP {
    /**
     * @var String Le pseudo du joueur
     */
	private $pseudo;

    /**
     * @var int Le nombre de partie jouées
     */
	private $nbParties;

    /**
     * @var int Le nombre de parties gagnées
     */
	private $nbPartiesGagnees;

    /**
     * @var float Le nombre de coups pour gagner
     */
	private $nbCoupsPourGagner;

	/**
	 * Le constructeurs de StatistiqueP (stats des joueurs)
	 * @param $pseudo String le pseudo du joueur
	 * @param $nbParties int nombre de parties jouées, au total
	 * @param $nbPartiesGagnees int nombre de parties gagnées, au total
	 * @param $nbCoupsPourGagner double nombre moyens de coups nécessaires pour gagner
	 */
	public function __construct($pseudo, $nbParties, $nbPartiesGagnees, $nbCoupsPourGagner) {
	    // les statistiuques pour un joueur se composent de son pseudo, son nombre de parties jouées,
        // son nombre de parties gagnées et son nombre de coups pour gagner

		$this->pseudo = $pseudo;
		$this->nbParties = $nbParties;
		$this->nbPartiesGagnees = $nbPartiesGagnees;
		$this->nbCoupsPourGagner = $nbCoupsPourGagner;
	}

	/**
	 * @return int
	 */
	public function getNbParties() { return $this->nbParties; }

	/**
	 * @return int
	 */
	public function getNbPartiesGagnees() { return $this->nbPartiesGagnees; }

	/**
	 * @return int
	 */
	public function getNbCoupsPourGagner() { return $this->nbCoupsPourGagner; }

	/**
	 * @return String
	 */
	public function getPseudo() { return $this->pseudo; }
}