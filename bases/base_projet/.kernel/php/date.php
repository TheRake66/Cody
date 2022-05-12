<?php
namespace Kernel;



/**
 * Librairie gerant les dates
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel
 * @category Librarie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
class Date {

    /**
     * Defini le fuseau horraire
     * 
     * @param string Fuseau horraire
     * @return void
     */
    static function timezone($zone = null) {
        if (is_null($zone)) {
            $zone = Configuration::get()->region->timezone;
        }
        date_default_timezone_set($zone);
        Debug::log('Fuseau horaire défini sur "' . $zone . '".');
    }
    
}

?>