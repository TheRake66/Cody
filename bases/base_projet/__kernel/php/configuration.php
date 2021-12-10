<?php
// Librairie Configuration
namespace Kernel;



class Configuration {

	/**
	 * Configuration actuelle
	 */
	private static $current;


	/**
	 * Charge la configuration
	 */
	static function load() {
        Debug::log('Chargement de la configuration...', Debug::LEVEL_PROGRESS);
		try {
			self::$current = json_decode(file_get_contents('__kernel/configuration.json'));
		} catch (\Exception $e) {
            throw new \Exception('Impossible de se charger la configuration, message : "' . $e->getMessage() . '".');
		}
        Debug::log('Configuration chargée.', Debug::LEVEL_GOOD);
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
