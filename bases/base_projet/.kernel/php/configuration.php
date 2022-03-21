<?php
namespace Kernel;



/**
 * Librairie gerant la configuration du noyau
 */
class Configuration {

	/**
	 * Configuration actuelle
	 */
	private static $current;


	/**
	 * Charge la configuration
	 */
	static function load() {
		try {
			self::$current = json_decode(file_get_contents('.kernel/configuration.json'));
		} catch (\Exception $e) {
            trigger_error('Impossible de se charger la configuration, message : "' . $e->getMessage() . '" !');
		}
	}
	

	/**
	 * Retourne la configuration actuelle
	 * 
	 * @return object la configuration
	 */
	static function get() {
		return self::$current;
	}
	
}
