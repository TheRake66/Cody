<?php
namespace Kernel\IO;

use Kernel\Debug\Log;
use Kernel\Security\Configuration;


/**
 * Librairie gerant les dates
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO
 * @category Framework source
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
    static function setTimezone($zone = null) {
        if (is_null($zone)) {
            $zone = Configuration::get()->region->timezone;
        }
        date_default_timezone_set($zone);
        Log::add('Fuseau horaire défini sur "' . $zone . '".');
    }
    
}

?>