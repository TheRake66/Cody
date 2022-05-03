<?php
require_once '.kernel/php/autoloader.php';
use Kernel as k;



// Enregistre l'autoloader de classe
k\Autoloader::register();

// Charge la configuration
k\Configuration::load();

// Prepare l'event des erreurs
k\Error::handler();

// Demarre le flux de donnees
k\Stream::reset();

// Ajoute un separateur dans la log
k\Debug::separator();

// Active le protocole SSL (HTTPS)
k\Security\Protocol::enableSsl();

// Defini le fuseau horraire par defaut
k\Date::timezone();

// Lance une session
k\Session\Socket::start();

// Charge les routes
k\Router::load();

// Lance le superviseur
k\Supervisor::supervise();

?>