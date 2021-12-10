<?php
// Librairie Date
namespace Kernel;



class Date {

    /**
     * Defini le fuseau horraire
     * 
     * @param string Fuseau horraire
     */
    static function timezone($zone = 'Europe/Paris') {
        date_default_timezone_set($zone);
        Debug::log('Fuseau horaire défini sur ' . $zone);
    }
    
}

?>