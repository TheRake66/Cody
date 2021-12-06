<?php

require_once 'kernel/php/autoloader.php';

use Kernel as k;



// Enregistre l'autoloader de classe
k\Autoloader::register();

// Active le protocole SSL (HTTPS)
//l\Security::activerSSL();

// Defini le fuseau horraire par defaut
k\Date::timezone();

// Lance une session PHP
k\Session::start();

// Charge la configuration
k\Configuration::load();

// Prepare l'event des erreurs
k\Error::handler();

?>