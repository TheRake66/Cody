<?php
require_once(__DIR__ . '/php/io/autoloader.php');
use Kernel as k;



// Enregistre l'autoloader de classe.
k\Io\Autoloader::register();

// Charge la configuration.
k\Security\Configuration::load();

// Prépare l'écouteur d'événement des erreurs.
k\Debug\Error::handler();

// Démarre le flux de données.
k\Io\Stream::reset();

// Active le protocole SSL (HTTPS).
k\Security\Ssl::enable();

// Défini le fuseau horraire par défaut.
k\Io\Convert\Date::timezone();

// Démarre une session.
k\Session\Socket::start();

// Charge les routes.
k\Url\Router::load();

// Vérifie si on demande une API.
k\Communication\Rest::check();

// Lance le superviseur.
k\Debug\Supervisor::watch();

?>