<?php
namespace Kernel\IO;

use Kernel\Debug\Error;



/**
 * Librairie chargeant les classes demandées.
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
     * @var string Les types de classes supportés.
     */
    const TYPE_CONTROLLER = 'Controller';
    const TYPE_MODEL = 'Model';
    const TYPE_API = 'Api';
    const TYPE_LIBRAIRY = 'Library';
    const TYPE_TEST = 'Test';
    const TYPE_TRAIT = 'Reflect';
    const TYPE_KERNEL = 'Kernel';
    

    /**
     * Initialise l'autoloader.
     * 
     * @return void
     */
    static function register() {
        if (!spl_autoload_register(function($class) {
            self::load($class);
        })) {
            die('Impossible d\'enregistrer la fonction d\'autoload !');
        }
    }


    /**
     * Vérifie si une classe existe même si elle n'est pas encore chargée.
     * 
     * @param string $class La classe à vérifier.
     * @return bool True si la classe existe, false sinon.
     */
    static function exists($class) {
        return class_exists($class, false) || !empty(self::file($class));
    }


    /**
     * Retourne le type de la classe.
     * 
     * @example type('Kernel\Io\Autoloader') => Kernel
     * @example type('Foo\Bar') => Foo
     * @param string $class La classe à vérifier.
     * @return string Le type de la classe.
     */
    static function typeof($class) {
        $_ = explode('\\', $class);
        return array_shift($_);
    }
    

    /**
     * Charge la classe demandée.
     * 
     * @param string $required La classe à charger.
     * @return void
     * @throws Error Si la classe n'existe pas.
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
     * Détermine le chemin du fichier de la classe demandée.
     * 
     * @param string $required La classe à charger.
     * @return string|null Le chemin du fichier, null si le file n'existe pas.
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
            case self::TYPE_KERNEL:
                $relative = '.kernel/php/' . $namespace_lower . '/' . $class_lower . '.php';
                break;

            case self::TYPE_LIBRAIRY:
                $relative = 'debug/lib/php/' . $namespace_lower . '/' . $class_lower . '.php';
                break;

            case self::TYPE_CONTROLLER:
                $relative = 'debug/app/' . $namespace_lower . '/' . $class_lower . '/' . $class_lower . '.php';
                break;

            case self::TYPE_MODEL:
                $relative = 'debug/data/' . $namespace_lower . '/' . $class_lower . '.php';
                break;

            case self::TYPE_API:
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