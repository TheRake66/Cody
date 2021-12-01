<?php

require_once 'librairie/php/autoloader.php';

use Librairie as l;

/**
 * Cree une nouvelle instance pour l'autoloader de classe
 */
new l\Autoloader();

/**
 * Active le protocole SSL (HTTPS)
 */
//l\Security::activerSSL();

/**
 * Defini le fuseau horraire par defaut
 */
l\Date::timezone();

/**
 * Lance une session PHP
 */
l\Session::createSession();

?>