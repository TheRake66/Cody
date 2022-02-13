<?php
namespace Kernel;



// Librairie Autoloader
class Autoloader {

    /**
     * Initialise l'autoloader
     */
    static function register() {
        spl_autoload_register('Kernel\Autoloader::load');
    }
    

    /**
     * Cherche et inclut les fichiers contenant les classes
     * Namespace\Classe
     * 
     * @param string Namespace
     */
    static function load($required) {
        // Contoleur\Carte\Main
        // composant/carte/main/cont.main.php

        $_ = explode('\\', $required);
        $class = end($_);
        $first = array_shift($_);
        $namespace = implode('/', array_slice($_, 0, -1));

        $file = '';
        switch ($first) {
            case 'Kernel':
                $file = '.kernel/php/' . $namespace . '/' . $class . '.php';
                break;

            case 'Librairy':
                $file = 'debug/lib/php/' . $namespace . '/' . $class . '.php';
                break;

            case 'Controler':
                $file = 'debug/app/' . $namespace . '/' . $class . '/cont.' . $class . '.php';
                break;

            case 'Model':
                $file = 'debug/data/' . $namespace . '/' . $class . '.php';
                break;

            default:
				$file = strtolower(str_replace('\\', '/', $required)) . '.php';
                break;
        }

        $file = strtolower($file);
        if (!is_file($file) && !is_readable($file)) {
            $file = str_replace('_', ' ', $file);
        }
        if(is_file($file) && is_readable($file)) {
            include $file;
        } else {
            throw new \Exception('Impossible de charger : "' . $file . '".');
        }
    }
    
}

?>