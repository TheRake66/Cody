<?php
namespace Kernel\Io\Convert;

use Kernel\Debug\Log;
use Kernel\Security\Configuration;


/**
 * Librairie gérant les dates.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Io\Convert
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Date {

    /**
     * Définit ou le fuseau horraire.
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
     * Calcul le temps d'exécution d'une fonction.
     * 
     * @param string $function La fonction à mesurer.
     * @return float Le temps d'exécution en millisecondes.
     */
    static function elapsed($callback) {
        $started = microtime(true);
        $callback();
        $ended = microtime(true);
        $time = round(($ended - $started) * 1000);
        return $time;
    }
    
}

?>