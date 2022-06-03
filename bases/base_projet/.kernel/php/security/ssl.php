<?php
namespace Kernel\Security;

use Kernel\Debug\Log;
use Kernel\Security\Configuration;
use Kernel\Url\Location;



/**
 * Librairie gerant le protocole SSL (Secure Socket Layer)
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Security
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Ssl {

	/**
	 * Verifie si le protocole SSL est actif, sinon redirige vers le protocole HTTPS
	 * 
	 * @return void
	 */
	static function enable() {
		if (Configuration::get()->security->redirect_to_https) {
			if(self::active()) {
				Log::add('SSL actif.', Log::LEVEL_GOOD);
			} else {
				Log::add('Activation du SSL...', Log::LEVEL_PROGRESS);
				Location::change('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			}
		}
	}


	/**
	 * Verifie si le protocole SSL est actif
	 * 
	 * @return bool si le protocole SSL est actif
	 */
	static function active() {
		return !($_SERVER['SERVER_PORT'] !== 443 &&
		(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off'));
	}

}

?>