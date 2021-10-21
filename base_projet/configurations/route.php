<?php
use Librairie\Routeur;

# Route vers Accueil
Routeur::go('accueil', function() {
	new Controleur\Accueil();
});

?>