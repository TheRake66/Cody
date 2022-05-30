<?php
namespace Kernel\IO;

use Kernel\Debug\Error;



/**
 * Librairie chargeant les classes demandees
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\IO
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Autoloader {

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
        if ($file = self::file($required)) {
            require_once($file);
            if (!class_exists($required)) {
                die('La classe "' . $required . '" n\'existe pas dans le fichier "' . $file . '" !');
            }
        } else {
            $msg = 'Impossible de charger la classe "' . $required . '" !';
            if (self::exists('Kernel\\Debug\\Error')) {
                Error::trigger($msg);
            } else {
                die($msg);
            }
        }
    }


    /**
     * Verifi si une classe existe meme si elle n'est pas encore chargee
     * 
     * @param string l'espace de nom de la classe
     * @return bool true si la classe existe
     */
    static function exists($class) {
        return class_exists($class, false) || !empty(self::file($class));
    }


    /**
     * Retourne le type de la classe
     * 
     * @example type('Kernel\IO\Autoloader') => Kernel
     * @example type('Foo\Bar') => Foo
     * @param string l'espace de nom de la classe
     * @return string le type de la classe
     */
    static function typeof($class) {
        $_ = explode('\\', $class);
        return array_shift($_);
    }


    /**
     * Determine le chemin du fichier de la classe demandee
     * 
     * @param string l'espace de nom de la classe
     * @return string|null le chemin du fichier ou null si non trouvé
     */
    private static function file($required) {
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

            case 'Library':
                $relative = 'debug/lib/php/' . $namespace_lower . '/' . $class_lower . '.php';
                break;

            case 'Controller':
                $relative = 'debug/app/' . $namespace_lower . '/' . $class_lower . '/controller.' . $class_lower . '.php';
                break;

            case 'Model':
                $relative = 'debug/data/' . $namespace_lower . '/' . $class_lower . '.php';
                break;

            case 'API':
                $relative = 'debug/api/' . $namespace_lower . '/' . $class_lower . '.php';
                break;

            default:
				$relative = strtolower(str_replace('\\', '/', $required_lower)) . '.php';
                break;
        }
        
        $root = dirname(dirname(dirname(__DIR__))) . '/';
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