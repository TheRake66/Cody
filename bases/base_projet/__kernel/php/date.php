<?php
// Librairie Date
namespace Kernel;



class Date {

    /**
     * Defini le fuseau horraire
     * 
     * @param string Fuseau horraire
     */
    static function timezone($zone = null) {
        if (is_null($zone)) {
            $zone = Configuration::get()->fuseau_horraire;
        }
        date_default_timezone_set($zone);
        Debug::log('Fuseau horaire défini sur ' . $zone . '.');
    }
    
}

?>