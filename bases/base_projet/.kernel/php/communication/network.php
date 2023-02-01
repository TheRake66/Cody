<?php
namespace Kernel\Communication;



/**
 * Librairie gérant les fonctions réseaux.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Communication
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
abstract class Network {

	/**
	 * Retourne l'adresse IP du client.
	 *
     * @return string L'adresse IP du client.
	 */
	static function client() {
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
	

	/**
	 * Retourne si l'adresse IP du client est celle de localhost.
	 * 
	 * @return bool True si l'adresse IP du client est celle de localhost sinon false.
	 */
	static function localhost() {
		return self::client() === "::1";
	}
	
}

?>