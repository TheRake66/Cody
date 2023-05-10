<?php

namespace Kernel\Security;

use Kernel\Debug\Error;
use Kernel\Io\File;



/**
 * Librairie gerant la configuration du noyau
 *
 * @author Thibault Bustos (TheRake66)
 * @version 2.0.0.0
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
		$jsonc = File::load('.kernel/configuration.jsonc');
		$pattern = '~("[^"\\\\]*(?:\\\\.[^"\\\\]*)*")|//[^\r\n]*|/\*.*?\*/~s';
		$json = preg_replace($pattern, '$1', $jsonc);
		self::$current = json_decode($json);
		if (is_null(self::$current)) {
			exit('Impossible de charger la configuration !');
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
			exit('La configuration n\'est pas chargée !');
		}
	}
	
}

?>