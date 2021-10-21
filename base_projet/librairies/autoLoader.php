<?php

namespace Librairie;



class Autoloader {

    /*
     * Chemin ou chercher les classes
     */
	static $paths = [];


    /*
     * Constructeur
     */
    function __construct() {
        spl_autoload_register('Librairie\Autoloader::autoloadAllsPath');
    }
   

    /*
     * Destructeur
     */
    function __destruct() {
    }


    /**
     * Ajoute une route
     */
	static function search($chemins) {
		self::$paths = $chemins;
	}


    /**
     * Cherche et inclut les fichiers contenant les classes
     * Namespace\Classe
     * 
     * @param string Namespace
     */
    static function autoloadAllsPath($namespace) {

        $exp = explode('\\', $namespace, PHP_INT_MAX);
        $class = strtolower(end($exp));

        foreach (self::$paths as $path) {
            $file = "{$path}/{$class}.php";
            if(is_file($file) && is_readable($file)) {
                require_once $file;
                break;
            }
        }
    }
    
}

?>