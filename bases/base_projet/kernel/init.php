<?php

require_once 'kernel/php/autoloader.php';

use Kernel as k;

/**
 * Cree une nouvelle instance pour l'autoloader de classe
 */
new k\Autoloader();

/**
 * Active le protocole SSL (HTTPS)
 */
//l\Security::activerSSL();

/**
 * Defini le fuseau horraire par defaut
 */
k\Date::timezone();

/**
 * Lance une session PHP
 */
k\Session::initSession();

?>