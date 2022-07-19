<?php
namespace Kernel\Communication\Network;

use Kernel\Security\Ssl;

/**
 * Librairie gérant les informations du serveur.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Communication\Network
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Server {

	/**
	 * Retourne l'adresse IP du serveur.
	 *
     * @return string L'adresse IP du serveur.
	 */
	static function ip() {
		if (!empty($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		} else {
			return 'localhost';
		}
	}
	

	/**
	 * Retourne le port du serveur.
	 * 
	 * @return string Le port du serveur.
	 */
	static function port() {
		if (!empty($_SERVER['REMOTE_PORT'])) {
			return $_SERVER['REMOTE_PORT'];
		} else {
			return Ssl::active() ? '443' : '80';
		}
	}

}

?>