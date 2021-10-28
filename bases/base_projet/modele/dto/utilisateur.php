<?php

namespace Modele\dto;
use Modele\Reflect\Hydrate;



class Utilisateur {

    use Hydrate;
	public $numUtilisateur;
	public $identifiant;
	public $motDePasse;
	public $sel;
	public $dateInscription;
	public $codeTypeUtilisateur;
	
}

?>