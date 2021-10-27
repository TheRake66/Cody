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
     * 
     * @param string Namespace\Class
     */
    static function load($required) {

        // Contoleur\Carte\Main
        // composant/carte/cont.main.php

        $_ = explode('\\', $required);
        $class = end($_);
        $first = array_shift($_);
        $namespace = implode(array_slice($_, 1, -1));


        $file = '';
        switch ($first) {
            case 'Librairie':
                $file = str_replace($required, $first, 'librairie/php') . $namespace . '/' . $class . '.php';
                break;

            case 'Controleur':
                $file = str_replace($required, $first, 'composant') . $namespace . '/' . $class . '/cont.' . $class . '.php';
                break;
        }

        $file = strtolower($file);
        if(is_file($file) && is_readable($file)) {
            require_once $file;
        }
    }
    
}

?>