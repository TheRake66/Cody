<?php
error_reporting(E_ERROR | E_PARSE);
require_once(__DIR__ . '/php/io/autoloader.php');
use Kernel as k;
use Cody as c;



// Enregistre l'autoloader de classe.
k\Io\Autoloader::register();

// Charge la configuration.
k\Security\Configuration::load();

// Supprime l'écouteur d'événement des erreurs.
k\Debug\Error::remove();

// Lance la console
c\Console\Program::main();

?>