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
        if (!spl_autoload_register(function($class) {
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
            require($file);
            if (!class_exists($required)) {
                die('La classe "' . $required . '" n\'existe pas dans le fichier "' . $file . '" !');
            }
        } else {
            die('Impossible de charger la classe "' . $required . '" !');
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

        $class_lower = strtolower($class);
        $namespace_lower = strtolower($namespace);
        $required_lower = strtolower($required);

        $relative = '';
        switch ($first) {
            case 'Kernel':
                $relative = '.kernel/php/' . $namespace_lower . '/' . $class_lower . '.php';
                break;

            case 'Librairy':
                $relative = 'debug/lib/php/' . $namespace_lower . '/' . $class_lower . '.php';
                break;

            case 'Controler':
                $relative = 'debug/app/' . $namespace_lower . '/' . $class_lower . '/cont.' . $class_lower . '.php';
                break;

            case 'Model':
                $relative = 'debug/data/' . $namespace_lower . '/' . $class_lower . '.php';
                break;

            default:
				$relative = strtolower(str_replace('\\', '/', $required_lower)) . '.php';
                break;
        }
        
        $root = dirname(dirname(__DIR__)) . '/';
        $file = $root . $relative;
        if (is_file($file) && is_readable($file)) {
            return $file;
        } else {
            $file = $root . str_replace('_', ' ', $relative);
            if(is_file($file) && is_readable($file)) {
                return $file;
            }
        }
    }
    
}

?>