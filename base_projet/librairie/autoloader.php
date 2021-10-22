<?php

namespace Librairie;



class Autoloader {

    /**
     * Constructeur
     */
    function __construct() {
        spl_autoload_register('Librairie\Autoloader::load');
    }
   

    /**
     * Destructeur
     */
    function __destruct() {
    }


    /**
     * Cherche et inclut les fichiers contenant les classes
     * Namespace\Classe
     * 
     * @param string Namespace
     */
    static function load($class) {
        $file = strtolower(str_replace('\\', '/', $class)) . '.php';
        if(is_file($file) && is_readable($file)) {
            require_once $file;
        }
    }
    
}

?>