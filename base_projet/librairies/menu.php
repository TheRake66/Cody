<?php

// ####################################################################################################
class Menu {

    // -------------------------------------------------------
	private $style;
	private $composants = [];
	private $menu;
    // -------------------------------------------------------
	


    // -------------------------------------------------------
	public function __construct($unStyle ){
		$this->style = $unStyle;
	}
    // -------------------------------------------------------



    // -------------------------------------------------------
	public function afficherMenu() {
		echo $this->menu;
	}

	public function ajouterComposant($unComposant){
		$this->composants[] = $unComposant;
	}
    // -------------------------------------------------------
	


    // -------------------------------------------------------
	public function creerItemLien($unLien,$uneValeur){
		$composant = array();
		$composant[0] = $unLien ;
		$composant[1] = $uneValeur ;
		return $composant;
	}
	
	public function creerMenu($composantActif,$nomMenu){
		$this->menu = "<ul class = '" .  $this->style . "' id = '" .  $this->style . "'>";
		foreach($this->composants as $composant){
			if($composant[0] == $composantActif){
				$this->menu .= "<li class='actif'>";
				$this->menu .=  "<span>" . $composant[1] ."</span>";
			}
			else{
				$this->menu .= "<li>";
				$this->menu .= "<a href='index.php?" . $nomMenu ;
				$this->menu .= "=" . $composant[0] . "' >";
				$this->menu .= "<span>" . $composant[1] ."</span>";
				$this->menu .= "</a>";
			}
			$this->menu .= "</li>";
		}
		$this->menu .= "</ul>";
	}
    // -------------------------------------------------------
	
}
// ####################################################################################################