<?php
namespace Kernel\Environnement;

use Kernel\Debug\Error;
use Kernel\Io\Autoloader;
use Kernel\Io\File;
use Kernel\Io\Path;



/**
 * Librairie gerant la configuration du noyau
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\System
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Configuration {

    /**
     * @var string Le fichier de configuration du framework.
     */
    const FILE_CONFIGURATION = '.kernel/configuration.json';

	/**
	 * @var array La configuration du noyau.
	 */
	private static $current;


	/**
	 * Charge la configuration.
	 * 
	 * @return bool True si la configuration a ete chargee, false sinon.
	 * @throws \Kernel\Debug\Error Si la configuration n'est pas trouvée.
	 */
	static function load() {
		$json = File::load(self::FILE_CONFIGURATION);
		self::$current = json_decode($json);
		if (self::$current !== null) {
			return true;
		} else {
            $msg = 'Impossible de charger la configuration !';
            if (Autoloader::exists('Kernel\\Debug\\Error')) {
                Error::trigger($msg);
            } else {
                die($msg);
            }
			return false;
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
            $msg = 'La configuration n\'est pas chargée !';
            if (Autoloader::exists('Kernel\\Debug\\Error')) {
                Error::trigger($msg);
            } else {
                die($msg);
            }
		}
	}
	
}
