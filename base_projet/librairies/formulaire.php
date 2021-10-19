<?php

// ####################################################################################################
class Formulaire{

    // -------------------------------------------------------
	private $method;
	private $action;
	private $id;
	private $class;
	private $enctype;
	private $formulaireToPrint;
	
	private $tabHTML = array();
    // -------------------------------------------------------
	


    // -------------------------------------------------------
	public function __construct($uneMethode, $uneAction, $unId = '', $uneClass = '', $unEnctype = '') {
		$this->method = $uneMethode;
		$this->action =$uneAction;
		$this->id = $unId;
		$this->class = $uneClass;
		$this->enctype = $unEnctype;
	}
    // -------------------------------------------------------
	


    // -------------------------------------------------------
	public function build() {
		$this->formulaireToPrint = "
		<form method='{$this->method}'
		action='{$this->action}' 
		id='{$this->id}' 
		class='{$this->class}' 
		enctype='{$this->enctype}' >";
		
		foreach ($this->tabHTML as $unComposant) {
			$this->formulaireToPrint .= $unComposant ;
		}
		$this->formulaireToPrint .= "</form>";

		$this->tabHTML[] = $this->formulaireToPrint ;
	}

	public function print() {
		echo $this->formulaireToPrint ;
	}
    // -------------------------------------------------------



    // -------------------------------------------------------
	public function label($unLabel, $unId = "", $uneClass = "") {
		$this->tabHTML[] = "<label class='{$uneClass}' id='{$unId}'>{$unLabel}</label>";
	}
	
	public function titre($unTexte, $unNiveau = 1, $unId = "", $uneClass = "") {
		$this->tabHTML[] = "<h{$unNiveau} class='{$uneClass}' id='{$unId}'>{$unTexte}</h{$unNiveau}>";
	}

	public function paragh($unTexte, $unId = "", $uneClass = "") {		
		$this->tabHTML[] = "<p class='{$uneClass}' id='{$unId}'>{$unTexte}</p>";
	}

	public function labelFor($unFor,  $unTexte, $unId = "", $uneClass = "") {
		$this->tabHTML[] = "<label for='{$unFor}' class='{$uneClass}' id='{$unId}'>{$unTexte}</label>";
	}
	
	public function labelLink($uneDestination,  $unTexte, $unTaget = "", $unId = "", $uneClass = "") {
		$this->tabHTML[] = "<a class='{$uneClass}' id='{$unId}' href='{$uneDestination}' target='{$unTaget}'>{$unTexte}</a>";
	}
    // -------------------------------------------------------
	


    // -------------------------------------------------------
	public function debutDiv($unId = "", $uneClass = "") {
		$this->tabHTML[] = "<div class='{$uneClass}' id='{$unId}'>";
	}

	public function finDiv() {
		$this->tabHTML[] = "</div>";
	}

	public function debutA($hRef, $unId = "", $uneClass = "") {
		$this->tabHTML[] = "<a href='{$hRef}' class='{$uneClass}' id='{$unId}'>";
	}

	public function finA() {
		$this->tabHTML[] = "</a>";
	}

	public function debutUl($unId = "", $uneClass = "") {
		$this->tabHTML[] = "<ul class='{$uneClass}' id='{$unId}'>";
	}

	public function finUl() {
		$this->tabHTML[] = "</ul>";
	}

	public function debutLi($unId = "", $uneClass = "") {
		$this->tabHTML[] = "<li class='{$uneClass}' id='{$unId}'>";
	}

	public function finLi() {
		$this->tabHTML[] = "</li>";
	}

	public function autoDiv($unId = "", $uneClass = "") {
		$this->tabHTML[] = "<div class='{$uneClass}' id='{$unId}'></div>";
	}

	public function br() {
		$this->tabHTML[] = "</br>";
	}

	public function image($uneSource, $unDefautText = "", $unId = "", $uneClass = "") {
		$this->tabHTML[] = "<img class='{$uneClass}' id='{$unId}' src='{$uneSource}' alt='{$unDefautText}'/>";
	}
    // -------------------------------------------------------

	
	
    // -------------------------------------------------------
	public function inputTexte($uneValue , $required , $readonly, $placeholder , $pattern, $unId = "", $uneClass = "") {
		$composant = "<input type='text' class='{$uneClass}' id='{$unId}' value='{$uneValue}' placeholder='{$placeholder}' pattern = '{$pattern}'";
		if ($required) $composant .= " required";
		if ($readonly) $composant .= " readonly";
		$this->tabHTML[] = "{$composant}/>";
	}

	public function inputHidden($uneValue, $unId = "", $uneClass = "") {
		$this->tabHTML[] = "<input type='hidden' class='{$uneClass}' id='{$unId}' value='{$uneValue}'/>";
	}

	public function inputNumber($uneValue , $required , $readonly, $placeholder , $pattern, $unId = "", $uneClass = "") {
		$composant = "<input type='number' class='{$uneClass}' id='{$unId}' value='{$uneValue}' placeholder='{$placeholder}' pattern = '{$pattern}'";
		if ($required) $composant .= " required";
		if ($readonly) $composant .= " readonly";
		$this->tabHTML[] = "{$composant}/>";
	}

	public function inputCheck($uneValue, $estCheck, $unId = "", $uneClass = "") {
		$composant = "<input type='checkbox' class='{$uneClass}' id='{$unId}'";
		if ($estCheck) $composant .= " checked";
		$this->tabHTML[] = "{$composant}><label for='{$unId}'>{$uneValue}</label>";
	}

	public function inputRadio($uneValue, $estCheck, $unId = "", $uneClass = "") {
		$composant = "<input type='radio' class='{$uneClass}' id='{$unId}'";
		if ($estCheck) $composant .= " checked";
		$this->tabHTML[] = "{$composant}><label for='{$unId}'>{$uneValue}</label>";
	}
	
	public function inputPass($uneValue , $required , $readonly, $placeholder , $pattern, $unId = "", $uneClass = "") {
		$composant = "<input type='password' class='{$uneClass}' id='{$unId}' value='{$uneValue}' placeholder='{$placeholder}' pattern = '{$pattern}'";
		if ($required) $composant .= " required";
		if ($readonly) $composant .= " readonly";
		$this->tabHTML[] = "{$composant}/>";
	}
	
	public function inputFile($unId = "", $uneClass = "") {
		$this->tabHTML[] = "<input type='file' class='{$uneClass}' id='{$unId}'/>";
	}
	
	public function inputDate($readonly, $uneValue, $unId = "", $uneClass = "") {
		$composant = "<input type='date' class='{$uneClass}' id='{$unId}' value='{$uneValue}'";
		if ($readonly) $composant .= " readonly";
		$this->tabHTML[] = "{$composant}/>";
	}
	
	public function inputDateTime($readonly, $uneValue, $unId = "", $uneClass = "") {
		$composant = "<input type='datetime-local' class='{$uneClass}' id='{$unId}' value='{$uneValue}T00:00'";
		if ($readonly) $composant .= " readonly";
		$this->tabHTML[] = "{$composant}/>";
	}
	
	public function inputSubmit($uneValue, $onClick = "", $unId = "", $uneClass = "") {
		$this->tabHTML[] = "<input type='submit' class='{$uneClass}' id='{$unId}' value='{$uneValue}' onclick='{$onClick}'/>";
	}

	public function inputImage($uneSource, $unId = "", $uneClass = "") {
		$this->tabHTML[] = "<input type='image' class='{$uneClass}' id='{$unId}' src='{$uneSource}'/>";
	}
    // -------------------------------------------------------


	
    // -------------------------------------------------------
	public function select($options, $selected, $unId = "", $uneClass = "") {
		$composant = "<select class='{$uneClass}' id='{$unId}'>";
		foreach ($options as $option) {
			$composant .= "<option value='{$option}'";
			if ($option == $selected) $composant .= " selected";
			$composant .= ">{$option}</option>";
		}
		$this->tabHTML[] = "{$composant}</select>";
	}	
	
	public function table($uneListe, $unId = "", $uneClass = "") {
		$composant = "<table id='{$unId} class='{$uneClass}'><thead><tr>";

		//  le head avec la premier ligne de la liste
		foreach ($uneListe[0] as $laTete) {
			$composant .= "<td>{$laTete}</td>";
		}

		$composant .= "</tr></thead><tbody>";

		//  le corps avec le reste de la liste
		for ($i = 1; $i < count($uneListe); $i++) {
			
			// Determine les couleurs par pair
			$pair = $i % 2 == 0 ? 'pair' : 'impair';
			$composant .= "<tr class='{$pair}'>";
			foreach ($uneListe[$i] as $leCorps) {
				$composant .= "<td>{$leCorps}</td>";
			}
			$composant .= "</tr>";

		}
		
		$this->tabHTML[] = "{$composant}</tbody></table>";
	}
    // -------------------------------------------------------
	
}
// ####################################################################################################