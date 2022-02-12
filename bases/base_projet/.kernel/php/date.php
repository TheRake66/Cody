<?php
namespace Kernel;



// Librairie Date
class Date {

    /**
     * Defini le fuseau horraire
     * 
     * @param string Fuseau horraire
     */
    static function timezone($zone = null) {
        if (is_null($zone)) {
            $zone = Configuration::get()->timezone;
        }
        date_default_timezone_set($zone);
        Debug::log('Fuseau horaire défini sur ' . $zone . '.');
    }
    
}

?>