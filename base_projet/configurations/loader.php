<?php
use Librairie\Autoloader;

/**
 * Chemin ou chercher les classes
 */
Autoloader::search([
	'controleurs',
	'modeles/dto',
	'modeles/dao',
	'modeles/traits',
	'librairies',
]);

?>