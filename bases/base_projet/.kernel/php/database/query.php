<?php
namespace Kernel\Database;

use Kernel\DataBase\Output;
use Kernel\Database\Factory\Hydrate;
use PDO;



/**
 *  Librairie de gérant l'exécution des requêtes SQL.
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
     * Exécute une requête de mise à jour.
     * 
     * @param string $sql La requête SQL.
     * @param mixed $params Les paramètres de la requête.
     * @return bool True si la requête a été exécutée, false sinon.
     */
    static function execute($sql, $params = null) {
        return Output::log(Output::send($sql, $params)->errorCode() === '00000');
    }

    
    /**
     * Exécute une requête de lecture d'une seule ligne.
     * 
     * @param string $sql La requête SQL.
     * @param mixed $params Les paramètres de la requête.
     * @return array La ligne de résultat.
     */
    static function row($sql, $params = null) {
        return Output::log(Output::send($sql, $params)->fetch(PDO::FETCH_ASSOC));
    }

    
    /**
     * Exécute une requête de lecture de plusieurs lignes.
     * 
     * @param string $sql La requête SQL.
     * @param mixed $params Les paramètres de la requête.
     * @return array Les lignes de résultat.
     */
    static function all($sql, $params = null) {
        return Output::log(Output::send($sql, $params)->fetchAll(PDO::FETCH_ASSOC));
    }

    
    /**
     * Exécute une requête de lecture d'une seule cellule.
     * 
     * @param string $sql La requête SQL.
     * @param mixed $params Les paramètres de la requête.
     * @return mixed La cellule de résultat.
     */
    static function cell($sql, $params = null) {
        $res = Output::send($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return Output::log($res ? (array_values($res)[0] ?? null) : null);
    }

    
    /**
     * Récupère une ligne et l'hydrate dans un objet DTO.
     * 
     * @param string $sql La requête SQL.
     * @param object|string $dto L'objet ou classe DTO.
     * @param mixed $params Les paramètres de la requête.
     * @return object L'objet DTO.
     */
    static function object($sql, $class, $params = null) {
        $_ = Output::send($sql, $params)->fetch(PDO::FETCH_ASSOC);
        return !empty($_) ? 
            Output::log(Hydrate::hydrate($_, $class)) :
            Output::log(null);
    }

    
    /**
     * Récupère plusieurs lignes et l'hydrate dans une liste d'objet DTO.
     * 
     * @param string $sql La requête SQL.
     * @param object|string $dto L'objet ou classe DTO.
     * @param mixed $params Les paramètres de la requête.
     * @return array La liste d'objet DTO.
     */
    static function objects($sql, $class, $params = null) {
        $_ = Output::send($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
        return !empty($_) ? 
            Output::log(Hydrate::hydrate($_, $class, true)) :
            Output::log([]);
    }

}

?>