<?php

namespace Kernel\Communication;



/**
 * Librairie gérant les fonctions réseaux.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @since Cody 7(21.65.0)
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 * @license MIT License
 * @package Kernel\Communication
 * @category Framework source
 */
abstract class Network {

	/**
	 * Retourne l'adresse IP du client.
	 *
	 * @access public
	 * @static
     * @return string L'adresse IP du client.
	 */
	static function client() {
		return $_SERVER['HTTP_CLIENT_IP'] ?? 
			   $_SERVER['HTTP_X_FORWARDED_FOR'] ??
			   $_SERVER['REMOTE_ADDR'] ??
			   '0.0.0.0';
	}
	

	/**
	 * Retourne si l'adresse IP du client est celle de localhost.
	 *
	 * @access public
	 * @static
	 * @return bool True si l'adresse IP du client est celle de localhost sinon false.
	 */
	static function localhost() {
		return self::client() === '::1';
	}
	
}

?>