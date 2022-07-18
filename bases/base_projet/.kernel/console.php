<?php
require_once(__DIR__ . '/php/io/autoloader.php');
use Kernel as k;
use Cody as c;



// Enregistre l'autoloader de classe.
k\Io\Autoloader::register();

// Supprime l'écouteur d'événement des erreurs.
k\Debug\Error::remove();

// Lance la console
c\Console\Program::main();

?>