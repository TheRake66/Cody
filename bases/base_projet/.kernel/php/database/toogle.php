<?php
namespace Kernel\Database;

use Kernel\Security\Configuration;
use Kernel\Database\Factory\Reflection;
use Kernel\Debug\Log;



/**
 * Librairie de gestions de la multibase de données.
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Toogle {
    
    /**
     * Change la base de données courante.
     * 
     * @param string $name Le nom de la base de données, si null, la base de données par défaut sera utilisée.
     * @return void
     */
    static function switch($database = null) {
        if (empty($database)) {
            $database = Configuration::get()->database->default;
        }
        if (Statement::current() != $database) {
            Statement::current($database);
        }
    }


    /**
     * Change la base de données courantes, exécute la fonction callback et remet la base de données courantes à la précédente.
     * 
     * @param callable $callback La fonction à exécuter.
     * @param string $database Le nom de la base de données, si null, la base de données par défaut sera utilisée.
     * @return mixed La valeur de retour de la fonction callback.
     */
    static function name($callback, $database = null) {
        $last = Statement::current();
        self::switch($database);
        $result = $callback();
        self::switch($last);
        return $result;
    }


    /**
     * Change la base de données courantes par la base de données d'un objet, exécute la fonction callback et remet la base de données courantes à la précédente.
     * 
     * @param callable $callback La fonction à exécuter.
     * @param string $type La classe ou l'objet DTO.
     * @return mixed La valeur de retour de la fonction callback.
     */
    static function object($callback, $type) {
        $database = Reflection::database($type);
        return !is_null($database) ? 
            self::name($callback, $database) :
            $callback();
    }

}

?>