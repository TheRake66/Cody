<?php
namespace Kernel\Database;
use Kernel\Configuration;
use Kernel\Debug;



/**
 * Librairie de gestions du multi-base de donnees
 */
class Toogle {
    
    /**
     * Change la base de donnees courante
     * 
     * @param string le nom de la base de donnees, si null, la base par defaut est utilisee
     * @return void
     */
    static function switch($database = null) {
        if (empty($database)) {
            $database = Configuration::get()->database->default_database;
        }
        if (Statement::getCurrent() != $database) {
            Statement::setCurrent($database);
            Debug::log('Changement de base de données vers "' . $database .'".', Debug::LEVEL_GOOD);
        }
    }


    /**
     * Change la base de donnees courante, execute la fonction callback et remet la base de donnees courante a la precedente
     * 
     * @param callable la fonction a executer
     * @param string le nom de la base de donnees, si null, la base par defaut est utilisee
     * @return mixed le resultat de la fonction
     */
    static function simple($callback, $database = null) {
        $last = Statement::getCurrent();
        self::switch($database);
        $result = $callback();
        self::switch($last);
        return $result;
    }


    /**
     * Si la classe type contient une constante DATABASE, on change la base de 
     * donnees courante, execute la fonction callback et remet la base de donnees 
     * courante a la precedente, sinon on execute la fonction callback sans changer
     * 
     * @param callable la fonction a executer
     * @param object la classe DTO
     * @return mixed le resultat de la fonction
     */
    static function object($callback, $type) {
        $file = (new \ReflectionClass($type))->getFileName();
        $path = dirname($file);
        $root = explode(DIRECTORY_SEPARATOR, $path);
        $database = array_pop($root);
        if ($database !== 'dto') {
            return self::simple($callback, $database);
        } else {
            return $callback();
        }
    }

}

?>