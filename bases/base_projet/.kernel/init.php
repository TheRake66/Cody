<?php
require_once(__DIR__ . '/php/io/autoloader.php');
use Kernel as k;



// Enregistre l'autoloader de classe
k\IO\Autoloader::register();

// Charge la configuration
k\Security\Configuration::load();

// Prepare l'event des erreurs
k\Debug\Error::handler();

// Demarre le flux de donnees
k\IO\Stream::reset();

// Ajoute un separateur dans la log
k\Debug\Log::separator();

// Active le protocole SSL (HTTPS)
k\Security\SSL::enable();

// Defini le fuseau horraire par defaut
k\IO\Convert\Date::setTimezone();

// Lance une session
k\Session\Socket::start();

// Charge les routes
k\URL\Router::load();

// Verifie si on demande une API
k\Communication\Rest::resting();

// Lance le superviseur
k\Debug\Supervisor::watch();

?>