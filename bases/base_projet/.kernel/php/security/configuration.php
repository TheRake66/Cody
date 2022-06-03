<?php
namespace Kernel\Security;

use Kernel\Debug\Error;
use Kernel\Io\Autoloader;
use Kernel\Io\File;
use Kernel\Io\Path;



/**
 * Librairie gerant la configuration du noyau
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Framework
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Configuration {

	/**
	 * @var array la configuration
	 */
	private static $current;


	/**
	 * Charge la configuration
	 * 
	 * @return void
	 */
	static function load() {
		$json = File::load('.kernel/configuration.json');
		self::$current = json_decode($json);
		if (self::$current === null) {
            $msg = 'Impossible de charger la configuration !';
            if (Autoloader::exists('Kernel\\Debug\\Error')) {
                Error::trigger($msg);
            } else {
                die($msg);
            }
		}
	}
	

	/**
	 * Retourne la configuration actuelle
	 * 
	 * @return object la configuration
	 * @throws Error si la configuration n'est pas chargee
	 */
	static function get() {
		if (!is_null(self::$current)) {
			return self::$current;
		} else {
            $msg = 'La configuration n\'est pas chargée !';
            if (Autoloader::exists('Kernel\\Debug\\Error')) {
                Error::trigger($msg);
            } else {
                die($msg);
            }
		}
	}
	
}
