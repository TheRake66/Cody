<?php
namespace Kernel\Environnement;

use Kernel\Debug\Log;
use Kernel\Environnement\Configuration;

/**
 * Librairie gérant l'environnement du programme.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Environnement
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class System {

    /**
     * @var string Les différents types d'environnement.
     */
    const OS_WINDOWS = 'WIN';
    const OS_LINUX = 'LIN';
    const OS_MAC = 'MAC';
    const OS_UNIX = self::OS_LINUX || self::OS_MAC;

    
    /**
     * Retourne le type d'environnement.
     * 
     * @return string Le type d'environnement.
     */
    static function os() {
        return strtoupper(substr(PHP_OS, 0, 3));
    }
    

    /**
     * Retourne la racide de l'environnement.
     * 
     * @return string Le chemin vers la racine de l'environnement.
     */
    static function root() {
        return dirname(dirname(dirname(__DIR__)));
    }


    /**
     * Définit le fuseau horraire.
     * 
     * @param string $zone Le fuseau horraire.
     * @return void
     */
    static function timezone($zone = null) {
        if (is_null($zone)) {
            $zone = Configuration::get()->region->timezone;
        }
        date_default_timezone_set($zone);
        Log::add('Fuseau horaire défini sur "' . $zone . '".');
    }

    
    /**
     * Définit la langue du système.
     * 
     * @param string $locale La langue.
     * @return void
     */
    static function locale($locale = null) {
        if (is_null($locale)) {
            $locale = Configuration::get()->region->locale;
        }
        setlocale(LC_ALL, $locale);
        Log::add('Langue locale définie sur "' . $locale . '".');
    }

}

?>