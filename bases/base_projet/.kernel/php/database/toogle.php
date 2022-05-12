<?php
namespace Kernel\Database;
use Kernel\Configuration;
use Kernel\Database\Factory\Reflection;
use Kernel\Debug;



/**
 * Librairie de gestions du multi-base de donnees
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database
 * @category Librarie
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
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
     * Si le fichier classe type a un dossier parent autre que dto, on change la base de 
     * donnees courante par la base portant le nom de ce dossier, on execute la fonction 
     * callback et remet la base de donnees courante a la precedente, sinon on execute
     * la fonction callback sans changer
     * 
     * @param callable la fonction a executer
     * @param object l'objet ou la classe DTO
     * @return mixed le resultat de la fonction
     */
    static function object($callback, $type) {
        $database = Reflection::getDatabaseName($type);
        return !is_null($database) ? 
            self::simple($callback, $database) :
            $callback();
    }

}

?>