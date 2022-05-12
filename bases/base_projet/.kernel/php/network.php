<?php
namespace Kernel;



/**
 * Librairie gerant les fonctions reseaux
 */
class Network {

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
		} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		} else {
			return '0.0.0.0';
		}
	}
	
}

?>