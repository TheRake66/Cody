<?php
namespace Kernel;



/**
 * Librairie gerant la configuration du noyau
 */
class Configuration {

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
		try {
			self::$current = json_decode(file_get_contents('.kernel/configuration.json'));
		} catch (\Exception $e) {
			die('Impossible de charger la configuration !');
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
			Error::trigger('La configuration n\'est pas chargée !');
		}
	}
	
}
