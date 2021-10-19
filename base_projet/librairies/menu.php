<?php

// ####################################################################################################
class Menu {

    // -------------------------------------------------------
	private $id;
	private $class;
	private $composants = [];
	private $menu;
    // -------------------------------------------------------
	


    // -------------------------------------------------------
	public function __construct($unId = "", $uneClass = "") {
		$this->id = $unId;
		$this->class = $uneClass;
	}
    // -------------------------------------------------------



    // -------------------------------------------------------
	public function print() {
		echo $this->menu;
	}
	
	public function lien($unLien, $uneValeur) {
		$composant = array();
		$composant[0] = $unLien ;
		$composant[1] = $uneValeur;
		$this->composants[] = $composant;
	}
	
	public function build($composantActif, $nomMenu) {
		$this->menu = "<nav id='{$this->id}' class='{$this->class}'><ul>";
		foreach ($this->composants as $composant) {

			$lien = $composant[0];
			$valeur = $composant[1];
			
			$this->menu .= $lien == $composantActif ? 
			"<li class='actif'><span>{$valeur}</span>" : 
			"<li><a href='index.php?{$nomMenu}={$lien}'><span>{$valeur}</span></a>";

			$this->menu .= "</li>";
		}
		$this->menu .= "</ul></nav>";
	}
    // -------------------------------------------------------
	
}
// ####################################################################################################