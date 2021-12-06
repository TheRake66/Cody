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
		self::$current = json_decode(file_get_contents('modele/database.json'));
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
