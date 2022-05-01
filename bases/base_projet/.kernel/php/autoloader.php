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
        if (!spl_autoload_register(function ($class) {
            self::load($class);
        })) {
            die(('Impossible d\'enregistrer la fonction d\'autoload !'));
        }
    }
    

    /**
     * Include la classe demandee
     * 
     * @param string l'espace de nom de la classe
     * @return void
     * @throws Error si le fichier n'est pas trouvé
     */
    private static function load($required) {
        if ($file = self::getFile($required)) {
            require $file;
            if (!class_exists($required)) {
                Error::trigger('La classe "' . $required . '" n\'existe pas dans le fichier "' . $file . '" !');
            }
        } else {
            Error::trigger('Impossible de charger la classe "' . $required . '" !');
        }
    }


    /**
     * Determine le chemin du fichier de la classe demandee
     * 
     * @param string l'espace de nom de la classe
     * @return string|null le chemin du fichier ou null si non trouvé
     */
    private static function getFile($required) {
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
            return $file;
        } else {
            $file = str_replace('_', ' ', $file);
            if(is_file($file) && is_readable($file)) {
                return $file;
            }
        }
    }


    /**
     * Verifi si une classe existe meme si elle n'est pas encore chargee
     * 
     * @param string l'espace de nom de la classe
     * @return bool true si la classe existe
     */
    static function classExist($class) {
        return class_exists($class, false) || !empty(self::getFile($class));
    }
    
}

?>