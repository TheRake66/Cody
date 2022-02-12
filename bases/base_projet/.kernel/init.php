<?php
ob_start(function($o) {
    return preg_replace(
        ['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/'], 
        ['>','<','\\1'], $o);
});
require_once '.kernel/php/autoloader.php';
use Kernel as k;



// Enregistre l'autoloader de classe
k\Autoloader::register();

// Charge la configuration
k\Configuration::load();

// Prepare l'event des erreurs
k\Error::handler();

// Lance le superviseur
k\Suppervisor::suppervise();

// Active le protocole SSL (HTTPS)
k\Security::enableSSL();

// Defini le fuseau horraire par defaut
k\Date::timezone();

// Lance une session PHP
k\Session::start();

// Charge les routes
k\Router::load();

?>