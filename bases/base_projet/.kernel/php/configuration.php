<?php
namespace Kernel;



/**
 * Librairie gerant la configuration du noyau
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel
 * @category Librarie
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
			$file = Path::absolute('.kernel/configuration.json');
			$json = file_get_contents($file);
			self::$current = json_decode($json);
		} catch (\Exception $e) {
			die('Impossible de charger la configuration ! Raison : ' . $e->getMessage());
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
