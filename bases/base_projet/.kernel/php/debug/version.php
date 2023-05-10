<?php

namespace Kernel\Debug;

use Kernel\Security\Configuration;



/**
 * Librairie de gestion de la version.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0.0.0
 * @package Kernel\Debug
 * @category Framework source
 * @license MIT License
 * @copyright © 2021-2023 - Thibault BUSTOS (TheRake66)
 */
abstract class Version {

	/**
	 * @var string La version de l'application.
	 */
	private static $beautiful;


    /**
     * Ajoute un log avec la version de l'application dans la console.
     * 
     * @return void
     */
    static function init() {
        $conf = Configuration::get()->version;
        self::$beautiful = $conf->release . ' ' . $conf->major . '.' . $conf->minor . '.' . $conf->patch . ' (' . $conf->build . ')';
        Log::add('Version de l\'application : ' . self::$beautiful . '.', Log::LEVEL_INFO);
    }


    /**
     * Retourne la version de l'application.
     * 
     * @return string La version de l'application.
     */
    static function get() {
        return self::$beautiful;
    }
    
}

?>