<?php
namespace Kernel;



/**
 * Librairie chargeant les classes demandees
 */
class Autoloader {

    /**
     * Initialise l'autoloader
     * 
     * @return void
     */
    static function register() {
        spl_autoload_register(function ($class) {
            self::load($class);
        });
    }
    

    /**
     * Cherche et inclut les fichiers contenant les classes

     * @param string Namespace
     * @return void
     * @throws \Exception Si le fichier n'est pas trouvé
     */
    private static function load($required) {
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
        if (is_file($file) && is_readable($file)) {
            include $file;
        } else {
            $file = str_replace('_', ' ', $file);
            if(is_file($file) && is_readable($file)) {
                include $file;
            } else {
                trigger_error('Impossible de charger la classe "' . $required . '" !');
            }
        }
    }
    
}

?>