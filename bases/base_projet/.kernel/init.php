<?php

require_once(__DIR__ . '/php/io/autoloader.php');

use Kernel as k;



// Enregistre l'autoloader de classe.
k\Io\Autoloader::register();

// Charge la configuration.
k\Security\Configuration::load();

// Prépare l'écouteur d'événement des erreurs.
k\Debug\Error::handler();

// Redirige vers la page de maintenance si nécessaire.
k\Debug\Maintenance::redirect();

// Active le protocole SSL (HTTPS).
k\Security\Ssl::enable();

// Récupère la base de donnée par défaut.
k\Database\Statement::init();

// Démarre le flux de données.
k\Io\Stream::reset();

// Récupère la version de l'application.
k\Debug\Version::init();

// Défini le fuseau horraire par défaut.
k\Io\Convert\Date::timezone();

// Charge les routes.
k\Url\Router::load();

// Vérifie si on demande une API.
k\Communication\Rest::check();

// Démarre une session.
k\Session\Socket::start();

// Lance le superviseur.
k\Debug\Supervisor::watch();

?>