<?php

use Librairie\Routeur;



# Route vers l'accueil
Routeur::go('accueil', function() {
	new Controleur\Accueil();
});

?>