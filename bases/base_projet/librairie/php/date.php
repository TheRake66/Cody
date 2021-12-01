<?php

namespace Librairie;



class Date {

    /**
     * Defini le fuseau horraire
     * 
     * @param string Fuseau horraire
     */
    static function timezone($zone = 'Europe/Paris') {
        date_default_timezone_set($zone);
    }
    
}

?>