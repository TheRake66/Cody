<?php
namespace Kernel\Communication;



/**
 * Librairie gerant les fonctions reseaux
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Communication
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Network {

	/**
	 * Retourne l'ip du client
	 *
     * @return string adresse ip
	 */
	static function getClientIP() {
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