<?php
require_once(__DIR__ . '/php/io/autoloader.php');
use Kernel as k;



// Enregistre l'autoloader de classe
k\Io\Autoloader::register();

// Charge la configuration
k\Security\Configuration::load();

// Prepare l'event des erreurs
k\Debug\Error::handler();

// Demarre le flux de donnees
k\Io\Stream::reset();

// Active le protocole SSL (HTTPS)
k\Security\Ssl::enable();

// Defini le fuseau horraire par defaut
k\Io\Convert\Date::timezone();

// Lance une session
k\Session\Socket::start();

// Charge les routes
k\Url\Router::load();

// Verifie si on demande une API
k\Communication\Rest::check();

// Lance le superviseur
k\Debug\Supervisor::watch();

?>