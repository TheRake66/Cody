<?php

namespace Kernel\Security;

use Kernel\Debug\Log;
use Kernel\Security\Configuration;
use Kernel\Url\Location;



/**
 * Librairie gérant le protocole SSL (Secure Socket Layer).
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @package Kernel\Security
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
abstract class Ssl {

	/**
	 * Vérifie si le protocole SSL est actif, sinon redirige vers le protocole HTTPS.
	 * 
	 * @return void
	 */
	static function enable() {
		if (Configuration::get()->security->only_https) {
			if(self::active()) {
				Log::good('SSL actif.');
			} else {
				Log::progress('Activation du SSL...');
				Location::change('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			}
		}
	}


	/**
	 * Vérifie si le protocole SSL est actif.
	 * 
	 * @return bool True si le protocole SSL est actif, false sinon.
	 */
	static function active() {
		return !($_SERVER['SERVER_PORT'] !== 443 &&
		(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off'));
	}

}

?>