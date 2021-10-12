<?php

// ####################################################################################################
class Formulaire{

    // -------------------------------------------------------
	private $method;
	private $action;
	private $nom;
	private $style;
	private $enctype;
	private $formulaireToPrint;
	
	private $ligneComposants = array();
	private $tabComposants = array();
    // -------------------------------------------------------
	


    // -------------------------------------------------------
	public function __construct($uneMethode, $uneAction , $unNom,$unStyle, $unEnctype = ""){
		$this->method = $uneMethode;
		$this->action =$uneAction;
		$this->nom = $unNom;
		$this->style = $unStyle;
		$this->enctype = $unEnctype;
	}
    // -------------------------------------------------------
	


    // -------------------------------------------------------
	public function concactComposants($unComposant , $autreComposant ){
		return $unComposant .= $autreComposant;
	}
	public function ajouterComposantLigne($unComposant){
		$this->ligneComposants[] = $unComposant;
	}
	public function ajouterComposantTab(){
		$this->tabComposants[] = $this->ligneComposants;
		$this->ligneComposants = array();
	}
    // -------------------------------------------------------
	


    // -------------------------------------------------------
	public function creerFormulaire(){
		$this->formulaireToPrint = "<form method = '" .  $this->method . "' ";
		$this->formulaireToPrint .= "action = '" .  $this->action . "' ";
		$this->formulaireToPrint .= "name = '" .  $this->nom . "' ";
		$this->formulaireToPrint .= "class = '" .  $this->style . "' ";
		$this->formulaireToPrint .= "enctype = '" .  $this->enctype . "' >";
		
		foreach ($this->tabComposants as $uneLigneComposants){
			foreach ($uneLigneComposants as $unComposant){
				$this->formulaireToPrint .= $unComposant ;
			}
		}
		
		$this->formulaireToPrint .= "</form>";
		return $this->formulaireToPrint ;
	}
	
	public function afficherFormulaire(){
		echo $this->formulaireToPrint ;
	}
	
	/* On ne peut pas imbriqué des balise form sinon le css et les post/get bug
	mais comme on a besoin d'un objet form pour créer le container qui va contenir les autres "sous" form
	on doit en créer un puis le fermer juste après
	*/
	public function useInCont(){
		$composant = "</form>";
		return $composant;
	}
    // -------------------------------------------------------



    // -------------------------------------------------------
	public function creerLabel($unNom, $unId, $unLabel){
		$composant = "<label name ='" . $unNom . "' id ='" . $unId . "'>" . $unLabel . "</label>";
		return $composant;
	}
	
	public function creerTitre($unTexte, $unNiveau = 1, $unId = ""){
		$composant = "<h" . $unNiveau;
		if ($unId != "") {
			$composant .= " id =" . $unId;
		}
		$composant .= ">" . $unTexte . "</h" . $unNiveau . ">";
		return $composant;
	}

	public function creerParagh($unTexte){		
		$composant = "<p>" . $unTexte . "</p>";
		return $composant;
	}

	public function creerLabelFor($unFor,  $unLabel){
		$composant = "<label for='" . $unFor . "'>" . $unLabel . "</label>";
		return $composant;
	}
	
	public function creerLabelLink($unNom, $unId, $uneDestination,  $unLabel, $unTaget = ""){
		$composant = "<a name ='" . $unNom . "' id ='" . $unId . "' href='" . $uneDestination . "' target='" . $unTaget . "'>" . $unLabel . "</a>";
		return $composant;
	}
    // -------------------------------------------------------
	


    // -------------------------------------------------------
	public function debutDiv($uneClass){
		$composant = "<div class='" . $uneClass . "' id='" . $uneClass . "'>";
		return $composant;
	}

	public function finDiv(){
		$composant = "</div>";
		return $composant;
	}

	public function debutA($hRef){
		$composant = "<a href='" . $hRef . "'>";
		return $composant;
	}

	public function finA(){
		$composant = "</a>";
		return $composant;
	}

	public function debutUl(){
		$composant = "<ul>";
		return $composant;
	}

	public function finUl(){
		$composant = "</ul>";
		return $composant;
	}

	public function debutLi(){
		$composant = "<li>";
		return $composant;
	}

	public function finLi(){
		$composant = "</li>";
		return $composant;
	}

	public function autoDiv($uneClass){
		$composant = "<div class='" . $uneClass . "' id='" . $uneClass . "'></div>";
		return $composant;
	}

	public function br(){
		$composant = "</br>";
		return $composant;
	}

	public function creerImage($uneClass, $uneSource, $unDefautText = ""){
		$composant = "<img class='" . $uneClass . "' src='" . $uneSource . "' alt='" . $unDefautText . "'/>";
		return $composant;
	}
    // -------------------------------------------------------

	
	
    // -------------------------------------------------------
	public function creerInputTexte($unNom, $unId, $uneValue , $required , $readonly, $placeholder , $pattern){
		$composant = "<input type = 'text' name = '" . $unNom . "' id = '" . $unId . "' ";
		if (!empty($uneValue)){
			$composant .= "value = '" . $uneValue . "' ";
		}
		if (!empty($placeholder)){
			$composant .= "placeholder = '" . $placeholder . "' ";
		}
		if ($required == 1){
			$composant .= "required ";
		}
		if ($readonly == 1){
			$composant .= "readonly ";
		}
		if (!empty($pattern)){
			$composant .= "pattern = '" . $pattern . "' ";
		}
		$composant .= "/>";
		return $composant;
	}

	public function creerInputHidden($unNom, $unId, $uneValue){
		$composant = "<input type = 'hidden' name = '" . $unNom . "' id = '" . $unId . "' ";
		if (!empty($uneValue)){
			$composant .= "value = '" . $uneValue . "' ";
		}
		$composant .= "/>";
		return $composant;
	}

	public function creerInputNumber($unNom, $unId, $uneValue , $required , $readonly, $placeholder , $pattern){
		$composant = "<input type = 'number' name = '" . $unNom . "' id = '" . $unId . "' ";
		if (!empty($uneValue)){
			$composant .= "value = '" . $uneValue . "' ";
		}
		if (!empty($placeholder)){
			$composant .= "placeholder = '" . $placeholder . "' ";
		}
		if ($required == 1){
			$composant .= "required ";
		}
		if ($readonly == 1){
			$composant .= "readonly ";
		}
		if (!empty($pattern)){
			$composant .= "pattern = '" . $pattern . "' ";
		}
		$composant .= "/>";
		return $composant;
	}

	public function creerInputCheck($unNom, $unId, $uneValue, $estCheck){
		$composant = "<div><input type = 'checkbox' name = '" . $unNom . "' id = '" . $unId . "'";

		if ($estCheck){
			$composant .= " checked";
		}

		$composant .= "><label for='" . $unId . "'>" . $uneValue . "</label></div>";
		return $composant;
	}

	public function creerInputRadio($unNom, $unId, $uneValue, $estCheck){
		$composant = "<div><input type = 'radio' name = '" . $unNom . "' id = '" . $unId . "' value = '" . $uneValue . "'";

		if ($estCheck){
			$composant .= " checked";
		}

		$composant .= "><label for='" . $unId . "'>" . $uneValue . "</label></div>";
		return $composant;
	}
	
	public function creerInputMdp($unNom, $unId,  $required , $placeholder , $pattern){
		$composant = "<input type = 'password' name = '" . $unNom . "' id = '" . $unId . "' ";
		if (!empty($placeholder)){
			$composant .= "placeholder = '" . $placeholder . "' ";
		}
		if ($required == 1){
			$composant .= "required ";
		}
		if (!empty($pattern)){
			$composant .= "pattern = '" . $pattern . "' ";
		}
		$composant .= "/>";
		return $composant;
	}
	
	public function creerInputFile($unNom, $unId){
		$composant = "<input type = 'file' name = '" . $unNom . "' id = '" . $unId . "'/>";
		return $composant;
	}
	
	public function creerInputDate($unNom, $unId,  $readonly, $uneValue){
		$composant = "<input type = 'date' name = '" . $unNom . "' id = '" . $unId . "'";
		
		if ($readonly == 1){
			$composant .= "readonly ";
		}
		if ($uneValue != null) {
			$composant .= " value='" . $uneValue . "'";
		}
		$composant .= "/>";
		return $composant;
	}
	
	public function creerInputDateTime($unNom, $unId, $readonly, $uneValue){
		$composant = "<input type = 'datetime-local' name = '" . $unNom . "' id = '" . $unId . "'";
		
		if ($readonly == 1){
			$composant .= "readonly ";
		}
		if ($uneValue != null) {
			$composant .= " value='" . $uneValue . "T00:00'";
		}
		$composant .= "/>";
		return $composant;
	}
	
	public function creerInputSubmit($unNom, $unId, $uneValue, $onClick = ""){
		$composant = "<input type = 'submit' name = '" . $unNom . "' id = '" . $unId . "' ";
		$composant .= "value = \"" . $uneValue . "\"";
		if ($onClick != "") {
			$composant .= " onclick='" . $onClick . "'";
		}
		
		$composant .= "/> ";
		return $composant;
	}

	public function creerInputImage($unNom, $unId, $uneSource){
		$composant = "<input type = 'image' name = '" . $unNom . "' id = '" . $unId . "' ";
		$composant .= "src = '" . $uneSource . "'/> ";
		return $composant;
	}
    // -------------------------------------------------------


	
    // -------------------------------------------------------
	public function creerSelect($unNom, $unId, $options, $selected){
		$composant = "<select  name = '" . $unNom . "' id = '" . $unId . "' >";
		foreach ($options as $option){
			$composant .= "<option value = '" . $option . "'";
			if ($option == $selected) {
				$composant .= " selected";
			}
			$composant .= ">" . $option . "</option>";
		}
		$composant .= "</select></td></tr>";
		return $composant;
	}	
	
	public function creerTable($uneClasse, $uneListe){
		$composant = "<table class='" . $uneClasse . "'><thead><tr>";

		// Creer le head avec la premier ligne de la liste
		foreach ($uneListe[0] as $laTete) {
			$composant .= "<td>" . $laTete . "</td>";
		}

		$composant .= "</tr></thead><tbody>";

		// Creer le corps avec le reste de la liste
		for ($i = 1; $i < count($uneListe); $i++) {
			
			// Determine les couleurs par pair
			if ($i%2==0) {
				$composant .= "<tr class='pair'>";
			} else {
				$composant .= "<tr class='impair'>";
			}
			
			foreach ($uneListe[$i] as $leCorps) {
				$composant .= "<td>" . $leCorps . "</td>";
			}
			
			$composant .= "</tr>";

		}
		$composant .= "</tbody></table>";

		return $composant;
	}
    // -------------------------------------------------------
	
}
// ####################################################################################################