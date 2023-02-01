<?php
namespace Kernel\Security;

use Kernel\Debug\Error;
use Kernel\Io\Autoloader;
use Kernel\Io\File;



/**
 * Librairie gerant la configuration du noyau
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Framework
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
abstract class Configuration {

	/**
	 * @var array La configuration du noyau.
	 */
	private static $current;


	/**
	 * Charge la configuration.
	 * 
	 * @return void
	 * @throws Error Si la configuration n'est pas trouvée.
	 */
	static function load() {
		$json = File::load('.kernel/configuration.json');
		self::$current = json_decode($json);
		if (is_null(self::$current)) {
            self::trigger('Impossible de charger la configuration !');
		}
	}
	

	/**
	 * Retourne la configuration actuelle.
	 * 
	 * @return object La configuration.
	 * @throws Error Si la configuration n'est pas chargée.
	 */
	static function get() {
		if (!is_null(self::$current)) {
			return self::$current;
		} else {
            self::trigger('La configuration n\'est pas chargée !');
		}
	}


	/**
	 * Déclenche une erreur en vérifiant si la classe d'erreur est chargée.
	 * 
	 * @return void
	 * @throws Error Erreur si la classe est chargée.
	 */
	private static function trigger($message) {
		if (Autoloader::exists('Kernel\\Debug\\Error')) {
			Error::trigger($message);
		} else {
			exit($message);
		}
	}
	
}
