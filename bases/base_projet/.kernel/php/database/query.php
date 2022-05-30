<?php
namespace Kernel\Database;

use Kernel\DataBase\Output;
use Kernel\Database\Factory\Hydrate;
use PDO;



/**
 * Librairie de gerant l'execution des requetes SQL
 *
 * @author Thibault Bustos (TheRake66)
 * @version 1.0
 * @package Kernel\Database
 * @category Framework source
 * @license MIT License
 * @copyright © 2022 - Thibault BUSTOS (TheRake66)
 */
abstract class Query {

    /**
     * Execture une requete de mise a jour
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return bool si la requete a reussite
     */
    static function execute($sql, $params = []) {
        return Output::log(Output::send($sql, $params)->errorCode() === '00000');
    }

    
    /**
     * Retourne une ligne
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return array la ligne retournee
     */
    static function row($sql, $params = []) {
        return Output::log(Output::send($sql, $params)->fetch(PDO::FETCH_ASSOC));
    }

    
    /**
     * Retourne plusieurs lignes
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return array les lignes retournees
     */
    static function all($sql, $params = []) {
        return Output::log(Output::send($sql, $params)->fetchAll(PDO::FETCH_ASSOC));
    }

    
    /**
     * Retourne une valeur
     * 
     * @param string requete sql
     * @param array liste des parametres
     * @return mixed valeur de la cellule
     */
    static function cell($sql, $params = []) {
        $res = Output::send($sql, $params)->fetch(PDO::FETCH_ASSOC);
        if (!is_null($res) && !empty($res)) {
            return Output::log(array_values($res)[0]);
        }
    }

    
    /**
     * Recupere une ligne et l'hydrate dans un objet DTO
     * 
     * @param string requete sql
     * @param object la classe DTO a hydrater
     * @param array la liste des parametres
     * @return object objet DTO hydrate
     */
    static function object($sql, $class, $params = []) {
        $_ = Output::send($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return !empty($_) ? 
            Output::log(Hydrate::hydrate($_, $class)) :
            Output::log(null);
    }

    
    /**
     * Recupere plusieurs lignes et les hydrate dans une liste d'objet DTO
     * 
     * @param string requete sql
     * @param object la classe DTO a hydrater
     * @param array la liste des parametres
     * @return array liste d'objets DTO hydrates
     */
    static function objects($sql, $class, $params = []) {
        $_ = Output::send($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        return !empty($_) ? 
            Output::log(Hydrate::hydrateMany($_, $class)) :
            Output::log([]);
    }

}

?>