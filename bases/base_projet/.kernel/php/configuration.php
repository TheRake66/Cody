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
	 * @throws Error si le fichier de configuration n'est pas trouvé
	 */
	static function load() {
		try {
			self::$current = json_decode(file_get_contents('.kernel/configuration.json'));
		} catch (\Exception $e) {
            Error::trigger('Impossible de charger la configuration.', $e);
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
