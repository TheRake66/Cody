<?php
namespace Kernel\Io\Convert;

use Kernel\Debug\Log;
use Kernel\Environnement\Configuration;


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
     * Convertit un nombre flottant en heures.
     * 
     * @example Date::unfloat(7.5); => "7h30"
     * @param float $float Le nombre flottant à convertir.
     * @return string L'horraire en heures et minutes.
     */
    static function unfloat($float) {
        $hours = floor($float);
        $minutes = floor(($float - $hours) * 60);
        return $hours . 'h' . $minutes;
    }
    
    
    /**
     * Convertit une horraire en nombre flottant.
     * 
     * @example Date::float("7h30"); => 7.5
     * @param string $string L'horraire à convertir.
     * @return float Le nombre flottant correspondant à l'horraire.
     */
    static function float($string) {
        $hours = substr($string, 0, strpos($string, 'h'));
        $minutes = substr($string, strpos($string, 'h') + 1);
        return $hours + $minutes / 60;
    }

}

?>