<?php
namespace Kernel;



/**
 * Librairie gerant les informations du serveur
 */
class Server {

	/**
	 * Retourne l'ip du client
	 *
     * @return string adresse ip
	 */
	static function getClientIp() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}
	
}

?>